<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ScreenAdminController extends Controller
{
    /**
     * @Route("/admin/screens", name="admin_screens")
     */
    public function listAction()
    {
    	$screens = $this->getDoctrine()->getRepository("AppBundle:Screen")->findAll();
    	
        return $this->render('admin/screen/list.html.twig', array('screens' => $screens));
    }


    /**
     * @Route("/admin/screen/create", name="admin_screen_create")
     */
    public function createAction()
    {
    	return $this->render('admin/screen/create.html.twig');
    }

    
    /**
     * @Route("/admin/screen/{screen}/edit", name="admin_screen_edit")
     */
    public function editAction($screen)
    {
    	$experiment = $this->getDoctrine()->getRepository("AppBundle:Experiment")->find($experiment);
    	
    	$screens = $experiment->getScreens();
    	
    	return $this->render('admin/screen/edit.html.twig', array('experiment' => $experiment, 'screens' => $screens));
    }
}
