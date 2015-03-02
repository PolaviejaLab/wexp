<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Player;
use AppBundle\Entity\Session;
use AppBundle\Entity\Response as AppResponse;
use AppBundle\Entity\Log;
use AppBundle\Helpers\JSON;

/**
 * The participant API controller handles communication
 * between the client and the server during an experiment. 
 */
class APIParticipantController extends Controller
{
	/**
	 * @Route("/api/participant/{participant_uuid}/finish", name="api_participant_finish")
	 */	
	public function finishAction($participant_uuid)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$participant = $this->getParticipantByUuid($participant_uuid);		
		$session = $participant->getSession();
		
		$status = $session->getStatus();
		
		if($status < 2) {
			$session->setStatus(2);
			$session->setStopped(new \DateTime('now'));
			$em->persist($session);
			$em->flush();
		}
	}
	

	/**
	 * @Route("/api/participant/{participant_uuid}/source", name="api_participant_source")
	 * 
	 * Interface for server sent events, that is push notifications from
	 * the server to the client.
	 */
	public function sseAction($participant_uuid)
	{
		$response = new \Symfony\Component\HttpFoundation\StreamedResponse(function() {
			ob_implicit_flush(true);
	
			$i = 0;
	
			while(true) {
				$this->sendMessage($i++, "Server time: " . microtime(true));
				sleep(1);
			}
		});
			 
		$response->headers->set('Content-Type', 'text/event-stream');
		$response->headers->set('Cache-Control', 'no-cache');
			 
		return $response;
	}	
	

    /**
     * @Route("/api/participant/{participant_uuid}/sink", name="api_participant_sink")
     * 
     * Interface that allows the client to report state back
     * to the server while the experiment is running.
     */
    public function sinkAction($participant_uuid)
    {	
    	$doc = $this->getDoctrine();
    	
    	// Decode JSON passed in POST message
    	$json = new JSON($this->get("request")->getContent());

    	// If JSON is not valid, return an error message
    	if(!$json->isValid()) {
    		$response = array('errorString' => "Failed to parse JSON: " . $json->getErrorString());
    		return new Response(json_encode($response));
    	}
    	
    	// Check whether data contains an array
    	$data = $json->getData();
    	
    	if(!is_array($data)) {
    		$response = array(
    				'errorString' => "Expected JSON to encode an array", 
    				'type' => gettype($data));
    		return new Response(json_encode($response));
    	}
    	
    	// Find participant
    	try {
			$participant = $this->getParticipantByUuid($participant_uuid);
    	} catch(\Exception $r) {
    		return new Response(json_encode(array(
   				"errorString" => $r->getMessage()
    		)));
    	}

    	$session = $participant->getSession();    	
    	$status = $session->getStatus();
    	
    	if($status == 2) {
    		return new Response(json_encode(array(
    			"errorString" => "The referenced experimental session has finished."	
    		)));
    	}
    	 
    	
		// Insert updates into the database
    	foreach($data as $field => $value)
			$this->updateResponse($participant, $field, $value);
    	    	
    	return new Response("{}");
    }

    
    /**
     * Returns a participant given a UUID
     *
     * @param string $participant UUID of the participant
     * @throws Response Response object in case of error
     * @return Participant object
     */
    private function getParticipantByUuid($participant)
    {
    	$doc = $this->getDoctrine();
    
    	// Retreive participant object
    	$participant = $doc->getRepository("AppBundle:Player")->findByUuid($participant);
    
    	if(count($participant) == 0)
    		throw new \Exception("Invalid participant identifier.");
    		
    	$participant = $participant[0];
    
    	// Check if session is still active
    	if($participant->getSession()->getStatus() > 1)
    		throw new \Exception("You are no longer participating in this experiment.");
    
    	return $participant;
    }
    
    
    private function getResponseRepository()
    {
    	if(!isset($this->responseRepository)) {
    		$doc = $this->getDoctrine();
    		$this->responseRepository = $doc->getRepository("AppBundle:Response");
    	}
    
    	return $this->responseRepository;
    }
    
    
    /**
     * Update a response in the database and create the
     * associated log item.
     *
     * @param Participant $participant
     * @param string $field
     * @param string $value
     */
    private function updateResponse($participant, $field, $value)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	// Create log entry of update
    	$timestamp = new \Datetime('now');
    
    	$entry = new Log();
    	$entry->setTimestamp($timestamp);
    	$entry->setPlayer($participant);
    	$entry->setField($field);
    	$entry->setMessage("U: " . $value);
    
    	$em->persist($entry);
    
    	// Find previous response
    	$response = $this->getResponseRepository()->findOneBy(
    			array('player' => $participant, 'field' => $field));
    
    	// Create new response if previous ones were found
    	if(!$response) {
    		$response = new AppResponse();
    		$response->setPlayer($participant);
    		$response->setField($field);
    	}
    
    	// Update timestamp and value
    	$response->setTimestamp($timestamp);
    	$response->setValue($value);
    
    	$em->persist($response);
    	$em->flush();
    }
}
