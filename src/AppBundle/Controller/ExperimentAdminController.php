<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExperimentAdminController extends Controller
{
    /**
     * @Route("/admin/experiments", name="experiments")
     */
    public function listAction()
    {
    	$experiments = $this->getDoctrine()->getRepository("AppBundle:Experiment")->findAll();
    	
        return $this->render('exp_admin/list.html.twig', array('experiments' => $experiments));
    }
    
}
