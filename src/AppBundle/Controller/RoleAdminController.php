<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RoleAdminController extends Controller
{
    /**
     * @Route("/admin/roles", name="admin_roles")
     */
    public function listAction()
    {
    	$roles = $this->getDoctrine()->getRepository("AppBundle:Role")->findAll();
    	
        return $this->render('admin/role/list.html.twig', array('roles' => $roles));
    }
}
