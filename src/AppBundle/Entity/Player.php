<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="experiment_player")
 */
class Player
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Experiment")
	 */
	protected $experiment;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Session")
	 */
	protected $session;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Role")
	 */
	protected $role;	

	/**
	 * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
	 */
	protected $user;
	
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set experiment
     *
     * @param \AppBundle\Entity\Experiment $experiment
     * @return Player
     */
    public function setExperiment(\AppBundle\Entity\Experiment $experiment = null)
    {
        $this->experiment = $experiment;

        return $this;
    }

    /**
     * Get experiment
     *
     * @return \AppBundle\Entity\Experiment 
     */
    public function getExperiment()
    {
        return $this->experiment;
    }

    /**
     * Set session
     *
     * @param \AppBundle\Entity\Session $session
     * @return Player
     */
    public function setSession(\AppBundle\Entity\Session $session = null)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get session
     *
     * @return \AppBundle\Entity\Session 
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set role
     *
     * @param \AppBundle\Entity\Role $role
     * @return Player
     */
    public function setRole(\AppBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \AppBundle\Entity\Role 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     * @return Player
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
