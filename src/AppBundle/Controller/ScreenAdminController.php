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
    

    private function getForm($screen, $experiment)
    {
    	$form = $this->createFormBuilder($screen)
    		->add('name', 'text')
    		->add('source', 'textarea', array('attr' => array('rows' => '15')))
    		->getForm();
    	
    	if($experiment == null) {
    		$form->add('experiment', 'entity', array(
    				'class' => 'AppBundle:Experiment',
    				'property' => 'name'
    		));
    	}

    	return $form;
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

    	// Create form
    	$form = $this->getForm($screen, $experiment);
    	$form->add('save', 'submit', array('label' => 'Create screen'));
    	$form->handleRequest($request);
    	
    	// Persist if valid
    	if($form->isValid()) {
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($screen);
    		$em->flush();
    		
    		return $this->redirect($this->generateUrl('admin_screen_edit', array('screen_id' => $screen->getId())));
    	}

    	return $this->render('admin/screen/create.html.twig', 
    			array('form' => $form->createView(), 'experiment' => $experiment));
    }

    
    /**
     * @Route("/admin/screen/{screen_id}/edit", name="admin_screen_edit")
     */
    public function editAction($screen_id, Request $request)
    {
    	$screen = $this->getDoctrine()->getRepository("AppBundle:Screen")->find($screen_id);
    	
    	if(!$screen) {
    		return $this->render('error.html.twig', array(
    				'object' => "screen #" . intval($screen_id),
    				'message' => "The specified screen could not be found.",
    		));
    	}    	
    	
    	// Create form
    	$form = $this->getForm($screen, null, $request);
    	$form->add('save', 'submit', array('label' => 'Update screen'));
    	$form->handleRequest($request);
    	 
    	// Persist if valid
    	if($form->isValid()) {
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($screen);
    		$em->flush();
    	
    		return $this->redirect($this->generateUrl('admin_experiment_view', array('experiment' => $screen->getExperiment()->getId())));
    	}
    	
    	return $this->render('admin/screen/edit.html.twig',
    			array('form' => $form->createView(), 'experiment' => null));
    	 
    }
}
