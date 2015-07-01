<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RoleAdminController extends Controller
{
    /**
     * @Route("/{_locale}/admin/roles", name="admin_roles", requirements={"_locale" = "%app.locales%"})
     */
    public function listAction()
    {
    	$roles = $this->getDoctrine()->getRepository("AppBundle:Role")->findAll();
    	$experiment = null;

        return $this->render('admin/role/list.html.twig', array('roles' => $roles, 'experiment' => $experiment));
    }

    /**
     * @Route("/{_locale}/admin/roles/by-experiment/{$experiment}", name="admin_roles_by_experiment", requirements={"_locale" = "%app.locales%"})
     */
   public function listByExperimentAction($experiment)
   {
		$experiment = $this->getDoctrine()->getRepository("AppBundle:Experiment")->find($experiment);
		$roles = $this->getDoctrine()->getRepository("AppBundle:Role")->findByExperiment($experiment);

		return $this->render('admin/role/list.html.twig', array('roles' => $roles, 'experiment' => $experiment));
   }
}
