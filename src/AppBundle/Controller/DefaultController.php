<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", defaults = {"_locale" = "en"})
     * @Route("/{_locale}", name="homepage", requirements = {"_locale" = "%app.locales%"})
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('experiments'));
    }


    /**
     * @Route("/{_locale}/admin", name="admin_dashboard", requirements = {"_locale" = "%app.locales%"})
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
