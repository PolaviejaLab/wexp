<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExperimentAdminController extends Controller
{
    /**
     * @Route("/{_locale}/admin/experiments", name="admin_experiments", requirements={"_locale" = "%app.locales%"})
     */
    public function listAction()
    {
    	$experiments = $this->getDoctrine()->getRepository("AppBundle:Experiment")->findAll();

        return $this->render('admin/experiment/list.html.twig', array('experiments' => $experiments));
    }


    /**
     * @Route("/{_locale}/admin/experiment/create", name="admin_experiment_create", requirements={"_locale" = "%app.locales%"})
     */
    public function createAction()
    {
    	return $this->render('admin/experiment/create.html.twig');
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

}
