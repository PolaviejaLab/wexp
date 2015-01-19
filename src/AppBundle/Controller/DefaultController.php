<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }
    
    /**
     * @Route("/exp", name="exp_homepage")
     */    
    public function experimentAction()
    {
    	if (false === $this->get('security.authorization_checker')->isGranted('ROLE_PARTICIPANT'))
    	{
    		throw $this->createAccessDeniedException();
    	}
    	
    	return $this->render('default/index.html.twig');
    }
}
