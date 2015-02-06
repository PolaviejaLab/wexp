<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExperimentAdminController extends Controller
{
    /**
     * @Route("/admin/experiments", name="admin_experiments")
     */
    public function listAction()
    {
    	$experiments = $this->getDoctrine()->getRepository("AppBundle:Experiment")->findAll();
    	
        return $this->render('admin/experiment/list.html.twig', array('experiments' => $experiments));
    }


    /**
     * @Route("/admin/experiment/create", name="admin_experiment_create")
     */
    public function createAction()
    {
    	return $this->render('admin/experiment/create.html.twig');
    }

    
    /**
     * @Route("/admin/experiment/{experiment}", name="admin_experiment_view")
     */
    public function viewAction($experiment)
    {
    	$experiment = $this->getDoctrine()->getRepository("AppBundle:Experiment")->find($experiment);
    	 
    	$screens = $experiment->getScreens();
    	 
    	return $this->render('admin/experiment/view.html.twig', array('experiment' => $experiment, 'screens' => $screens));
    }    
}
