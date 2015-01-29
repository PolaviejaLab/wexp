<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Player;


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
     * @Route("/experiment/{id}/lobby", name="experiment_lobby")
     */
    public function lobbyAction($id)
    {
    	$experiment = $this->getDoctrine()->getRepository("AppBundle:Experiment")->find($id);
    	$user = $this->getUser();

    	if(!$experiment)
    		return new Response("Invalid experiment ID");
    	
    	// Check if participant is eligible to participate in this experiment.    	
    	if(!$user)
    		return new Response("Invalid user ID");
    	
    	/* Check if an (unassigned) actor has already been created */
		$player = $this->getDoctrine()->getRepository("AppBundle:Player")->findOneBy(
				array('experiment' => $id, 'session' => null, 'role' => null, 'user' => $user->getId()));
		
		/* Register actor */
    	if(!$player) {
  	    	$player = new Player();
    		$player->setExperiment($experiment);
			$player->setUser($user);
    	
			$em = $this->getDoctrine()->getManager();
			$em->persist($player);
			$em->flush();
    	}
		
    	/**
    	 * Render experiment page where the script will wait for a signal
    	 * from the server that a session id is available.
    	 */
    	return $this->render('experiment/lobby.html.twig', array('player' => $player));
    }
    
    
    private function sendMessage($id, $message)
    {
    	echo("id: $id" . PHP_EOL);
    	echo("data: $message" . PHP_EOL);
    	echo(PHP_EOL);
    
    	flush();
    }
    
    
    /**
     * @Route("/experiment/sse", name="experiment_sse")
     */
    public function sseAction()
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
