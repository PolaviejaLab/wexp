<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Screen;


class ScreenAdminController extends Controller
{
    /**
     * @Route("/admin/screens", name="admin_screens")
     */
    public function listAction()
    {
    	$screens = $this->getDoctrine()->getRepository("AppBundle:Screen")->findAll();
    	$experiment = null;
    	
        return $this->render('admin/screen/list.html.twig', array('screens' => $screens, 'experiment' => null));
    }


    /**
     * @Route("/admin/screens/by-experiment/{$experiment}", name="admin_screens_by_experiment")
     */
    public function listByExperimentAction($experiment)
    {
    	$experiment = $this->getDoctrine()->getRepository("AppBundle:Experiment")->find($experiment);
    	$screens = $this->getDoctrine()->getRepository("AppBundle:Screen")->findByExperiment($experiment);
    	 
    	return $this->render('admin/screen/list.html.twig', array('screens' => $screens, 'experiment' => $experiment));    	 
    }
    

    /**
     * @Route("/admin/screen/create/{experiment}", name="admin_screen_create", defaults={"experiment" = null})
     */
    public function createAction($experiment, Request $request)
    {
    	$screen = new Screen();
    	
    	if($experiment) {
    		$experiment = $this->getDoctrine()->getRepository("AppBundle:Experiment")->find($experiment);
    		$screen->setExperiment($experiment);
    	}    	
    	
    	$form = $this->createFormBuilder($screen)
    		->add('name', 'text')
    		->getForm();

    	if($experiment == null) {
    		$form->add('experiment', 'entity', array(
    				'class' => 'AppBundle:Experiment',
    				'property' => 'name'
    		));    		
    	}
    	
    	$form->add('save', 'submit', array('label' => 'Create screen'));
    	$form->handleRequest($request);
    	
    	if($form->isValid()) {
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($screen);
    		$em->flush();
    		
    		return $this->redirect($this->generateUrl('admin_screen_edit', array('screen' => $screen->getId())));
    	}
    	
    	return $this->render('admin/screen/create.html.twig', 
    			array('form' => $form->createView(), 'experiment' => $experiment));
    }

    
    /**
     * @Route("/admin/screen/{screen}/edit", name="admin_screen_edit")
     */
    public function editAction($screen)
    {
    	$screen = $this->getDoctrine()->getRepository("AppBundle:Screen")->find($screen);
    	
    	return $this->render('admin/screen/edit.html.twig', array('screen' => $screen));
    }
}
