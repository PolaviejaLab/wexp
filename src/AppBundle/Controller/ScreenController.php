<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ScreenController extends Controller
{
    /**
     * @Route("/screen/get/{experiment_id}/{screen_name}")
     */
    public function getAction($experiment_id, $screen_name)
    {
        return $this->render('screen/screen.xml');
    }
    
      
    private function loadXML($resource)
    {
    	$location = $this->get('kernel')->locateResource($resource);
    	$contents = file_get_contents($location);
    	
    	return $this->parseXML($contents);
    }
    	
    private function parseXML($contents)
    {
    	libxml_use_internal_errors(true);
    	
    	$doc = new \DOMDocument();
    	$doc->strictErrorChecking = true;
    	
    	if(!$doc->loadXML($contents)) {
    		$errors = libxml_get_errors();
			$error_string = '';
    		
    		foreach($errors as $error) {
    			$error_string .= $this->XMLErrorToString($error) . "\n";
    		}

    		throw new \Exception($error_string);
    	}
    	
    	return $doc;
    }
    
    
    /**
     * @Route("/screen/compile/{experiment_id}/{screen_name}")
     */
    public function compileAction($experiment_id, $screen_name)
    {
    	$screen = $this->getDoctrine()->getRepository("AppBundle:Screen")->findOneByName($screen_name);    	
    	$screen = $this->parseXML($screen->getSource());
    	
    	$xsl = $this->loadXML('@AppBundle/Resources/screen/screen.xsl');

    	$xslt = new \XSLTProcessor();
    	$xslt->importStylesheet($xsl);
    	
    	$transformed = $xslt->transformToXML($screen);    	
    	
    	$response = $transformed . "\n"; 
    	
    	/*$doc = new \DOMDocument();
    	$doc->formatOutput = true;
    	$doc->loadXML($response);    	
    	$response = $doc->saveXML();*/
    	
    	//return new Response("XML: [" . $response . "]");
    	return $this->render('default/screen.html.twig', array("content" => $response));
    }

    
    
    private function XMLErrorToString(\libXMLError $error) {
    	return "Line " . $error->line . 
    	       ", column " . $error->column . 
    	       ": " . $error->message;
    }
}
