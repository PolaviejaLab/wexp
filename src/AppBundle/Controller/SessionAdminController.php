<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Screen;
use Symfony\Component\HttpFoundation\Response;


class SessionAdminController extends Controller
{
    /**
     * @Route("/admin/sessions", name="admin_sessions")
     */
    public function listAction()
    {
    	$sessions = $this->getDoctrine()->getRepository("AppBundle:Session")->findAll();
    	$experiment = null;
    	
        return $this->render('admin/session/list.html.twig', array('sessions' => $sessions, 'experiment' => null));
    }


    /**
     * @Route("/admin/experiment/{experiment}/sessions", name="admin_sessions_by_experiment")
     */
    public function listByExperimentAction($experiment)
    {
    	$experiment = $this->getDoctrine()->getRepository("AppBundle:Experiment")->find($experiment);
    	$sessions = $this->getDoctrine()->getRepository("AppBundle:Session")->findAll($experiment);
    	 
    	return $this->render('admin/session/list.html.twig', array('sessions' => $sessions, 'experiment' => $experiment));    	 
    }
    
    
    /**
     * Generates a filename for the dataset, removing
     * all non-alpha numeric characters from the experiment name.
     *
     * @param $experiment Object describing the experiment
     * @param $extension Extension of the filename
     * @return string
     */
    private function generateDatasetFilename($experiment, $extension = "xls")
    {
    	$date = new \DateTime("now");
    
    	$filename = $date->format("Y-m-d") . "_";
    	$filename .= "dataset_";
    	$filename .= $experiment->getId() . "_";
    
    	$name = strtolower($experiment->getName());
    	$name = preg_replace("[^:alnum: ]", "", $name);
    	$name = str_replace(" ", "_", $name);
    
    	$filename .= $name . "." . $extension;
    
    	return $filename;
    }
    
    
    private function escapeCSV($str)
    {
    	echo(strpos(",", $str));
    	
    	if(strpos($str, ",") || strpos($str, '"'))
    		$str = '"' . str_replace('"', '""', $str) . '"';
    	return $str;
    }

    
    
    /**
     * @Route("/admin/experiment/{experiment}/dataset", name="admin_sessions_export")
     */
    public function datasetAction($experiment)
    {
    	$doc = $this->getDoctrine();
    	$experiment = $this->getDoctrine()->getRepository("AppBundle:Experiment")->find($experiment);
    	$result = $doc->getRepository("AppBundle:Experiment")->getDataset($experiment);    	

    	$csv = implode(",", $result['fields']) . "\n";
    	
    	foreach($result['data'] as $row) {
    		foreach(array_keys($row) as $index) {
    			$row[$index] = $this->escapeCSV($row[$index]);
    		}
    		
    		$csv .= implode(",", $row) . "\n";
    	}
    	
    	// Return response
    	$filename = $this->generateDatasetFilename($experiment);
    	
		$response = new Response($csv);
    	$response->headers->set('Content-Type', 'application/vnd.ms-excel');
    	//$response->headers->set('Content-Type', 'text/plain');
    	$response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

    	return $response;
    }
    
    
    /**
     * @Route("/admin/session/{session}/details", name="admin_session_details")
     */
    public function detailsAction($session) 
    {
    	$session = $this->getDoctrine()->getRepository("AppBundle:Session")->find($session);
    	$player = $this->getDoctrine()->getRepository("AppBundle:Player")->findBySession($session);
    	$responses = $this->getDoctrine()->getRepository("AppBundle:Response")->findByPlayer($player);
    	
    	return $this->render('admin/session/details.html.twig', array('session' => $session, 'player' => $player[0], 'responses' => $responses));    	
    }
    
}
