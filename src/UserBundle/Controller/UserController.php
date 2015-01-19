<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function loginAction(Request $request)
    {
    	$session = $request->getSession();
    	
    	// Get the login error if there is one
    	if($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
    		$error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
    	} elseif(null != $session && $session->has(Security::AUTHENTICATION_ERROR)) {
    		$error = $session->get(Security::AUTHENTICATION_ERROR);
    		$session->remove(Security::AUTHENTICATION_ERROR);
    	} else {
    		$error = '';
    	}
    	
    	// Last username entered
    	$lastUsername = (null === $session) ? '' : $session->get(Security::LAST_USERNAME);
    	
    	
        return $this->render(
        	'user/login.html.twig',
        	array('last_username' => $lastUsername, 'error' => $error));
    }
    
    
    public function loginCheckAction()
    {
    	
    }
}
