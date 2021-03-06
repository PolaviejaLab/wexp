<?php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;
use UserBundle\Entity\Role;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		/**
		 * To change the default password:
		 *   https://www.dailycred.com/blog/12/bcrypt-calculator
		 * The default password is: 'default'
		 */

		$admin_role = new Role();
		$admin_role->setName("Administrator");
		$admin_role->setRole("ROLE_ADMINISTRATOR");

		$researcher_role = new Role();
		$researcher_role->setName("Researcher");
		$researcher_role->setRole("ROLE_RESEARCHER");

		$participant_role = new Role();
		$participant_role->setName("Participant");
		$participant_role->setRole("ROLE_PARTICIPANT");


		$manager->persist($admin_role);
		$manager->persist($researcher_role);
		$manager->persist($participant_role);
		$manager->flush();


		$researcher = new User();
		$researcher->setUserName("researcher");
		$researcher->setPassword('$2a$12$7tLZIx.wHIjqmP8OrGAjxuilWqGZwZfpKrWYEOB1LPnoXVimdOlgm');
		$researcher->addRole($researcher_role);

		$user = new User();
		$user->setUsername("ivar");
		$user->setPassword('$2a$12$E.XrecDCBlTLsTa6pMnk2.SJbLlrJJD0A/YcdBbXRZX7GOFf6MGwS');
		$user->addRole($participant_role);

		$manager->persist($researcher);
		$manager->persist($user);
		$manager->flush();

		$this->addReference('user-researcher', $researcher);
	}

	public function getOrder()
	{
		return 1;
	}
}
