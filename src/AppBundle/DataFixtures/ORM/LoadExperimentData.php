<?php 

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;
use AppBundle\Entity\Role;
use AppBundle\Entity\Experiment;
use AppBundle\Entity\Screen;

class LoadExperimentData extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		// Multiplayer experiment
		/* $experiment = new Experiment();	
		$experiment->setName("Public Goods Game");
		$experiment->addOwner($this->getReference('user-researcher'));;
		$manager->persist($experiment);
		$manager->flush();
		
		foreach(array("Player A", "Player B", "Player C", "Player D") as $name) {
			$role = new Role();
			$role->setName($name);
			$role->setAmount(1);
			$role->setExperiment($experiment);

			$manager->persist($role);
			$manager->flush();
		}*/
		
		
		// Single player experiment
		foreach(array('Trust game', 'Dictator game') as $game) {		
			$experiment = new Experiment();
			$experiment->setName($game);
			$experiment->addOwner($this->getReference('user-researcher'));
		
			$manager->persist($experiment);
			$manager->flush();
		
			$role = new Role();
			$role->setName("Player");
			$role->setAmount(1);
			$role->setExperiment($experiment);
		
			$manager->persist($role);
			$manager->flush();
		}
		
		// Add screen
		$screenPath = realpath(dirname(__FILE__)) . "/dictator";		
		$screenDir = opendir($screenPath);
		
		while($filename = readdir($screenDir)) {
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			$name = pathinfo($filename, PATHINFO_FILENAME);
			
			if($ext == 'xml') {
				$screen = new Screen();
				$screen->setName($name);
				$screen->setExperiment($experiment);
				
				$screen->setSource(file_get_contents($screenPath . '/' . $filename));
				
				$manager->persist($screen);
				$manager->flush();
			}
		}		
	}
	
	
	public function getOrder()
	{
		return 2;
	}
}
