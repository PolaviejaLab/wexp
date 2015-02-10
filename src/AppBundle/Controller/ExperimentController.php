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
     * @Route("/experiment/{experiment_id}/run/{player_id}", name="experiment_run", defaults={"player_id" = null})
     */
    public function runAction($experiment_id, $player_id)
    {
    	$doc = $this->getDoctrine();
    	$em = $doc->getManager();

    	// Get experiment and user objects
    	$experiment = $doc->getRepository('AppBundle:Experiment')->find($experiment_id);
    	$user = $this->getUser();
    	
    	if(!$experiment)
    		return $this->render('error.html.twig',
    				array('message' => 'The experiment you are trying to participate in no longer exists.'));    	


    	/**
    	 * No player ID passed, assign a player ID and reload
    	 */
    	if(!$player_id) {
    		// Try to find existing player
    		if($user) {
    			$player = $doc->getRepository("AppBundle:Player")
    				->findCurrentPlayer($experiment->getId(), $user->getId());
    		} else {
    			$player = null;
    		}
    		
    		if(!$player) {
    			$player = new Player();
    			$player->setExperiment($experiment);
    			$player->setUser($user);

    			$em->persist($player);
    			$em->flush();
    		}

    		$player_id = $player->getId();
    		
    		return $this->redirect(
    				$this->generateUrl('experiment_run', 
    						array('experiment_id' => $experiment_id, 'player_id' => $player_id)));
    	}


    	// Get information about player
    	$player = $doc->getRepository('AppBundle:Player')->find($player_id);    	
    	
    	if(!$player)
    		return $this->render('error.html.twig',
    				array('message' => 'Invalid session / player identifier.'));


		// Load all screens and previous responses		
    	$screens = $doc->getRepository("AppBundle:Screen")->findByExperiment($experiment);
    	$responses = $doc->getRepository("AppBundle:Response")->findByPlayer($player);
    	
    	// Convert responses to JSON
    	$response_array = [];
    	foreach($responses as $response)
    		$response_array[$response->getField()] = $response->getValue();
    	
    	if(empty($response_array)) {
    		$responses = "{}";
    	} else {
    		$responses = json_encode($response_array);
    	}
    	
    	
    	/**
    	 * Render the experiment page
    	 */
    	return $this->render('experiment/lobby.html.twig', 
    			array('screens' => $screens, 'player' => $player, 'responses' => $responses));
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
