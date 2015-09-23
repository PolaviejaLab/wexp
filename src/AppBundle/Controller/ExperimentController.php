<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Experiment;
use AppBundle\Entity\Player;
use AppBundle\Entity\Session;
use AppBundle\Entity\Response as AppResponse;
use AppBundle\Entity\Log;


class ExperimentController extends Controller
{

	/**
	 * Create a new session for a given experiment.
	 *
	 * @param  \AppBundle\Entity\Experiment $experiment
	 * @return \AppBundle\Entity\Session
	 */
	private function startSession(Experiment $experiment)
	{
		$doc = $this->getDoctrine();
		$em = $doc->getEntityManager();

		$session = new Session();
		$session->setExperiment($experiment);
		$session->setStarted(new \DateTime('now'));
		$session->setStatus(0);

		$em->persist($session);
		$em->flush();

		return $session;
	}


	/**
	 * Returns a session with a free slot.
	 *
	 * @param unknown $experiment
	 */
	private function getSessionForExperiment(Experiment $experiment)
	{
		$doc = $this->getDoctrine();
		$em = $doc->getEntityManager();

		// Get information about rules
		$roles = $experiment->getRoles();
		$number_of_roles = count($roles);

		// Start new session for single-participant experiments
		if($number_of_roles == 1) {
			$session = $this->startSession($experiment);
			$session->setStatus(1);

			$em->persist($session);
			$em->flush();

			return $session;
		}

		// Find all sessions that are waiting for participants
		$sessions = $doc->getRepository('AppBundle:Session')->findByStatus(1);

		foreach($sessions as $session) {
			// FIXME: Check if the session has a free slot.
		}

		return null;
	}


	/**
	 * Creates a new participant in the database and
	 * calls session management functions.
	 */
	private function createParticipant(Experiment $experiment, $user)
	{
		$doc = $this->getDoctrine();
		$em = $doc->getEntityManager();

		// Setup participant
		$participant = new Player();
		$participant->setExperiment($experiment);
		$participant->setUser($user);

		$participant->setSession(
			$this->getSessionForExperiment($experiment));

		$em->persist($participant);
		$em->flush();

		return $participant;
	}


    /**
     * @Route("/{_locale}/experiment/list", name="experiments", requirements={"_locale" = "%app.locales%"})
     */
    public function listAction()
    {
    	$experiments = $this->getDoctrine()->getRepository("AppBundle:Experiment")->findBy(array("deleted" => false));

        return $this->render('experiment/list.html.twig', array('experiments' => $experiments));
    }


    /**
     * @Route("/{_locale}/experiment/{experiment_id}/run/{participant_uuid}", name="experiment_run", defaults={"participant_uuid" = null})
     */
    public function runAction($experiment_id, $participant_uuid)
    {
    	$doc = $this->getDoctrine();
    	$em = $doc->getManager();

    	// Get experiment and user objects
    	$experiment = $doc->getRepository('AppBundle:Experiment')->find($experiment_id);

    	if(!$experiment)
    		return $this->render('error.html.twig',
    				array('message' => 'The experiment you are trying to participate in no longer exists.'));

    	/**
    	 * No participant UUID passed, assign a UUID and reload
    	 */
    	if(!$participant_uuid) {
    		$user = $this->getUser();
    		$participant = null;

    		// If logged in, try to find previous session
    		if($user) {
    			$participant = $doc->getRepository("AppBundle:Player")
    				->findCurrentPlayer($experiment->getId(), $user->getId());
    		}

    		// Create new participant
    		if(!$participant) {
				$participant = $this->createParticipant($experiment, $user);
    		}

    		$participant_uuid = $participant->getUuid();

    		return $this->redirect(
    				$this->generateUrl('experiment_run',
    						array('experiment_id' => $experiment_id,
    							  'participant_uuid' => $participant_uuid)));
    	}


    	// Get information about player
    	$participant = $doc->getRepository('AppBundle:Player')->findByUuid($participant_uuid);

    	if(count($participant) != 1 || !$participant[0]) {
    		return $this->render('error.html.twig',
    				array('message' => 'Invalid session / player identifier.'));
    	}

    	$participant = $participant[0];


			// Load all screens and previous responses
    	$screens = $doc->getRepository("AppBundle:Screen")->findByExperiment($experiment);
    	$responses = $doc->getRepository("AppBundle:Response")->findByPlayer($participant);

    	// Convert responses to JSON
    	$response_array = [];
    	foreach($responses as $response)
    		$response_array[$response->getField()] = $response->getValue();

    	if(empty($response_array)) {
    		$responses = "{}";
    	} else {
    		$responses = json_encode($response_array);
    	}

    	$status = $participant->getSession()->getStatus();

    	if($status < 2) {
	    	/**
    		 * Render the experiment page
    	 	*/
    		return $this->render('experiment/run.html.twig',
    				array('screens' => $screens, 'participant' => $participant, 'responses' => $responses));
    	} else {
    		return $this->render('experiment/finished.html.twig',
    				array('participant' => $participant, 'responses' => $responses));
    	}
    }


    private function sendMessage($id, $message)
    {
    	echo("id: $id" . PHP_EOL);
    	echo("data: $message" . PHP_EOL);
    	echo(PHP_EOL);

    	flush();
    }
}
