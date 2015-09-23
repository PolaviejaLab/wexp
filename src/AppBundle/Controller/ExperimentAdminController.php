<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Experiment;

class ExperimentAdminController extends Controller
{
	private function getForm($experiment)
	{
		$form = $this->createFormBuilder($experiment)
			->add('name', 'text')
			->getForm();
	
		return $form;
	}
    
    
    /**
     * @Route("/{_locale}/admin/experiment/create", name="admin_experiment_create", requirements={"_locale" = "%app.locales%"})
     */
    public function createAction(Request $request)
    {
    	$experiment = new Experiment();
    	  	
    	// Create form
    	$form = $this->getForm($experiment);
    	$form->add('save', 'submit', array('label' => 'Create experiment'));
    	$form->handleRequest($request);
    	
    	// Persist if valid
    	if($form->isValid()) {
    		// Set owner to current user
    		$experiment->addOwner(
    				$this->get('security.context')->getToken()->getUser());
    		
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($experiment);
    		$em->flush();
    	
    		return $this->redirect($this->generateUrl('admin_experiment_view', array('experiment' => $experiment->getId())));
    	}
    	
    	return $this->render('admin/experiment/create.html.twig',
    			array('form' => $form->createView()));
    }


    /**
     * @Route("/{_locale}/admin/experiment/{experiment}", name="admin_experiment_view", requirements={"_locale" = "%app.locales%"})
     */
    public function viewAction($experiment)
    {
    	$experiment = $this->getDoctrine()->getRepository("AppBundle:Experiment")->find($experiment);

    	$screens = $experiment->getScreens();

    	return $this->render('admin/experiment/view.html.twig', array('experiment' => $experiment, 'screens' => $screens));
    }

    
    /**
     * @Route("/{_locale}/admin/experiment/{experiment}/remove", name="admin_experiment_remove", requirements={"_locale" = "%app.locales%"})
     */
    public function removeAction($experiment)
    {
		$experiment = $this->getDoctrine()->getRepository("AppBundle:Experiment")->find($experiment);
		$user = $this->get('security.context')->getToken()->getUser();
				
		if(!$experiment) {
			throw new \Exception("Invalid experiment");
		}
		
		if(!$experiment->isOwner($user)) {
			throw new \Exception("Only an owner can delete the experiment");
		}
		
		$experiment->setDeleted(true);
		
		$em = $this->getDoctrine()->getManager();
		$em->persist($experiment);
		$em->flush();
		
    	return $this->redirect($this->generateUrl('experiments'));    	
    }
}
