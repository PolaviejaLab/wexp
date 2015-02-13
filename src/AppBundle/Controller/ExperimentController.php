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
    			$session = new Session();
    			$session->setExperiment($experiment);
    			$session->setStarted(new \DateTime('now'));
    			$session->setStatus(1);
    			
    			$em->persist($session);
    			$em->flush();
    			
    			$player = new Player();
    			$player->setExperiment($experiment);
    			$player->setUser($user);
    			$player->setSession($session);

    			$em->persist($player);
    			$em->flush();
    		}

    		$player_id = $player->getUuid();
    		
    		return $this->redirect(
    				$this->generateUrl('experiment_run', 
    						array('experiment_id' => $experiment_id, 'player_id' => $player_id)));
    	}


    	// Get information about player
    	$players = $doc->getRepository('AppBundle:Player')->findByUuid($player_id);    	
    	$player = $players[0];
    	    	
    	if(!$player) {
    		return $this->render('error.html.twig',
    				array('message' => 'Invalid session / player identifier.'));
    	}


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
    
    

}
