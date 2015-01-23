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
    public function lobbyAcction($id)
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
}
