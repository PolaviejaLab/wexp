<?php 

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;
use AppBundle\Entity\Role;
use AppBundle\Entity\Experiment;

class LoadExperimentData extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$experiment = new Experiment();	
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
		}		
	}
	
	
	public function getOrder()
	{
		return 2;
	}
}
