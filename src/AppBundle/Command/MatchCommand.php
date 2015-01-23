<?php 

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Entity\Session;

class MatchFunction
{
	private $doctrine;
	private $em;
	
	private $experiment;
	private $sessions;
	
	private $roles;
	private $role_ids;
	
	private $players;
	
	private $players_per_session = 0;
	
	public function __construct($doctrine, $experiment)
	{
		$this->doctrine = $doctrine;
		$this->em = $this->doctrine->getManager();
		
		$this->experiment = $experiment;
		
		$this->role_ids = array();
		$this->roles = array();		
		
		// Count the number of players required in each session
		foreach($experiment->getRoles() as $role) {
			$this->players_per_session += $role->getAmount();
			
			$this->role_ids[] = $role->getId();
			$this->roles[$role->getId()] = $role;
		}

		// Find open sessions
		$this->sessions = $doctrine->getRepository("AppBundle:Session")->findBy(
				array('status' => 0));
		
		// Find players waiting for a match
		$this->players = $doctrine->getRepository("AppBundle:Player")->findBy(
				array('experiment' => $experiment->getId(), 'session' => null, 'role' => null));		
	}

	
	/**
	 * Start all sessions that have enough players
	 */
	public function startFullSessions()
	{
		foreach($this->sessions as $session)
		{
			$count = count($session->getPlayers());
				
			if($count == $this->players_per_session) {
				$session->setStatus(2);
				$session->setStarted(new \DateTime("now"));
				$this->em->persist($session);
				$this->em->flush();
			}
		}		
	}
	
	
	/**
	 * Setup an empty session
	 * @return \AppBundle\Entity\Session
	 */
	public function createSession()
	{
		$session = new Session();
		$session->setExperiment($this->experiment);
		$session->setStatus(0);
		$this->em->persist($session);
		$this->em->flush();
		
		return $session;
	}
	
	
	public function execute()
	{
		foreach($this->players as $player) {
			$session = null;
			$roles = $this->roles;
			
			// Find a role and session for this player
			foreach($this->sessions as $candidate) 
			{				
				$count = count($candidate->getPlayers());
				
				// Session has room
				if($count < $this->players_per_session) { 
					// Determine roles that are not present				
					foreach($candidate->getPlayers() as $p) {
						$role_id = $p->getRole()->getId();
						unset($roles[$role_id]);
					}

					$session = $candidate;
					
					break;
				}
			}

			// Pick random role
			$role = $roles[array_rand($roles)];				
			
			// No session found, add session
			if(!$session) {
				$session = $this->createSession();
			}
			
			if(!$session) {
				echo("Could not find session for player\n");
				return;
			}
			
			// Add player to session			
			$player->setSession($session);
			$session->addPlayer($player);			
			$player->setRole($role);
			$this->em->persist($player);
			$this->em->flush();
		}
		
		// Start sessions
		$this->startFullSessions();
	}
}


class MatchCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:match')
            ->setDescription('Match roles with participans')
        ;
    }

    
    protected function getDoctrine() 
    {
    	return $this->getContainer()->get('doctrine');
    }
    
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Matching participants...");
        $experiments = $this->getDoctrine()->getRepository("AppBundle:Experiment")->findAll();
        
        /**
         * Run the script for every experiment
         */
        foreach($experiments as $experiment) {
        	$output->writeln(" - " . $experiment->getName());
        	
        	/**
        	 * Get roles
        	 */
        	$roles = $experiment->getRoles();
        	
        	foreach($roles as $role) {
        		$output->writeln("   - " . $role->getName() . ": " . $role->getAmount());
        	}
        	
        	/**
        	 * Invoke matching function for every role/session
        	 */
        	$match = new MatchFunction($this->getDoctrine(), $experiment);
        	$match->execute();
        }
    }
}
