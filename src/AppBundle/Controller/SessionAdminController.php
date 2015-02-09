<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Screen;


class SessionAdminController extends Controller
{
    /**
     * @Route("/admin/sessions", name="admin_sessions")
     */
    public function listAction()
    {
    	$sessions = $this->getDoctrine()->getRepository("AppBundle:Session")->findAll();
    	$experiment = null;
    	
        return $this->render('admin/session/list.html.twig', array('sessions' => $sessions, 'experiment' => null));
    }


    /**
     * @Route("/admin/sessions/by-experiment/{$experiment}", name="admin_sessions_by_experiment")
     */
    public function listByExperimentAction($experiment)
    {
    	$experiment = $this->getDoctrine()->getRepository("AppBundle:Experiment")->find($experiment);
    	$sessions = $this->getDoctrine()->getRepository("AppBundle:Session")->findAll($experiment);
    	 
    	return $this->render('admin/session/list.html.twig', array('sessions' => $sessions, 'experiment' => $experiment));    	 
    }
}
