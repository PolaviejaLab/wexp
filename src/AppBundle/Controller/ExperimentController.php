<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Player;
use AppBundle\Entity\Session;
use AppBundle\Entity\Response as AppResponse;
use AppBundle\Entity\Log;

class ExperimentController extends Controller
{
    /**
     * @Route("/experiment/list", name="experiments")
     */
    public function listAction()
    {
    	$experiments = $this->getDoctrine()->getRepository("AppBundle:Experiment")->findAll();
    	
        return $this->render('experiment/list.html.twig', array('experiments' => $experiments));
    }


    /**
     * @Route("/experiment/{id}/run/{player}/sink", name="experiment_sink")
     */
    public function sinkAction($id, $player)
    {   	
    	$content = $this->get("request")->getContent();
    	
    	if(!empty($content)) {
    		$experiment = $this->getDoctrine()->getRepository("AppBundle:Experiment")->find($id);
    		$player = $this->getDoctrine()->getRepository("AppBundle:Player")->find($player);
    		$user = $this->getUser();    		
    		
    		$em = $this->getDoctrine()->getManager();    		
    		
    		$data = json_decode($content, true);
    		
    		$responseRepository = $this->getDoctrine()->getRepository("AppBundle:Response");
    		$now = new \Datetime('NOW');
    		
    		// id timestamp player field value
    		foreach($data as $field => $value) {
    			// Write log
				$entry = new Log();
				$entry->setTimestamp($now);
				$entry->setPlayer($player);
				$entry->setField($field);
				$entry->setMessage("U: " . $value);
				
				$em->persist($entry);
    			
    			// Update response
    			$response = $responseRepository->findOneBy(
    					array('player' => $player, 'field' => $field));
    			 
    			if(!$response) {
    				$response = new AppResponse();
    				$response->setPlayer($player);
    				$response->setField($field);
    			}
    			
    			$response->setTimestamp($now);
    			$response->setValue($value);
    			
    			$em->persist($response);
    			$em->flush();
    		}
    	}
    	
    	return new Response("{}");
    }
    
    
    /** 
     * @Route("/experiment/{id}/lobby", name="experiment_lobby")
     */
    public function lobbyAction($id)
    {
    	$experiment = $this->getDoctrine()->getRepository("AppBundle:Experiment")->find($id);
    	$user = $this->getUser();

    	if(!$experiment)
    		return $this->render('error.html.twig', 
    			array('message' => 'The experiment you are trying to participate in no longer exists.'));
    	
    	// Check if participant is eligible to participate in this experiment.    	
    	if(!$user)
    		return $this->render('error.html.twig', 
    			array('message' => 'You should be logged in to participate in an experiment.'));

    	// Try to find pre-existing player assignment
    	$player = $this->getDoctrine()->getRepository("AppBundle:Player")->findCurrentPlayer($experiment->getId(), $user->getId());
	
    	// Create new player
	   	if(!$player) {
    		$player = new Player();
   			$player->setExperiment($experiment);
			$player->setUser($user);
   	
			$em = $this->getDoctrine()->getManager();
			$em->persist($player);
			$em->flush();
    	}
		
    	$screens = $this->getDoctrine()->getRepository("AppBundle:Screen")->findByExperiment($experiment);
    	
    	// Previous responses as JSON
    	$responses = $this->getDoctrine()->getRepository("AppBundle:Response")->findByPlayer($player);
    	
    	$response_array = [];
    	foreach($responses as $response) {
    		$response_array[$response->getField()] = $response->getValue();
    	}
    	
    	$responses = json_encode($response_array);
    	
    	
    	/**
    	 * Render experiment page where the script will wait for a signal
    	 * from the server that a session id is available.
    	 */
    	return $this->render('experiment/lobby.html.twig', array('screens' => $screens, 'player' => $player, 'responses' => $responses));
    }
    
    
    private function sendMessage($id, $message)
    {
    	echo("id: $id" . PHP_EOL);
    	echo("data: $message" . PHP_EOL);
    	echo(PHP_EOL);
    
    	flush();
    }
    
    
    /**
     * @Route("/experiment/{id}/run/{player}/sse", name="experiment_sse")
     */
    public function sseAction($id, $player)
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
}
