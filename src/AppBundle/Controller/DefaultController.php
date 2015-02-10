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
        return $this->redirect($this->generateUrl('experiments'));
    }

    
    /**
     * @Route("/admin", name="admin_dashboard")
     */    
    public function experimentAction()
    {
    	if (false === $this->get('security.authorization_checker')->isGranted('ROLE_RESEARCHER'))
    	{
    		throw $this->createAccessDeniedException();
    	}
    	
    	return $this->redirect($this->generateUrl('admin_experiments'));
    }
}
