<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ExperimentRepository")
 * @ORM\Table(name="experiment")
 */
class Experiment
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToMany(targetEntity="UserBundle\Entity\User")
	 */
	protected $owners;
	
	/**
	 * @ORM\Column(type="string", length=100)
	 */
	protected $name = "New experiment";

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $deleted = false;
	
	/**
	 * @ORM\OneToMany(targetEntity="Role", mappedBy="experiment")	 
	 */
	protected $roles;
	
	/**
	 * @ORM\OneToMany(targetEntity="Session", mappedBy="experiment")
	 */
	protected $sessions;	
	
	/**
	 * @ORM\OneToMany(targetEntity="Screen", mappedBy="experiment")
	 */
	protected $screens;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Screen")
	 */
	protected $startScreen;
	
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
     * Set name
     *
     * @param string $name
     * @return Experiment
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->owners = new \Doctrine\Common\Collections\ArrayCollection();
        $this->screens = new \Doctrine\Common\Collections\ArrayCollection();
    }

    
    /**
     * Add owners
     *
     * @param \UserBundle\Entity\User $owners
     * @return Experiment
     */
    public function addOwner(\UserBundle\Entity\User $owners)
    {
        $this->owners[] = $owners;

        return $this;
    }

    /**
     * Remove owners
     *
     * @param \UserBundle\Entity\User $owners
     */
    public function removeOwner(\UserBundle\Entity\User $owners)
    {
        $this->owners->removeElement($owners);
    }

    /**
     * Checks whether the user is an owner
     * 
     * @param \UserBundle\Entity\User $user
     */
    public function isOwner(\UserBundle\Entity\User $user)
    {
    	foreach($this->owners as $owner) {
    		if($owner->getUsername() == $user->getUsername())
    			return true;
    	}
    	
    	return false;
    }
    
    /**
     * Get owners
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOwners()
    {
        return $this->owners;
    }

    /**
     * Add roles
     *
     * @param \AppBundle\Entity\Role $roles
     * @return Experiment
     */
    public function addRole(\AppBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \AppBundle\Entity\Role $roles
     */
    public function removeRole(\AppBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Add sessions
     *
     * @param \AppBundle\Entity\Session $sessions
     * @return Experiment
     */
    public function addSession(\AppBundle\Entity\Session $sessions)
    {
        $this->sessions[] = $sessions;

        return $this;
    }

    /**
     * Remove sessions
     *
     * @param \AppBundle\Entity\Session $sessions
     */
    public function removeSession(\AppBundle\Entity\Session $sessions)
    {
        $this->sessions->removeElement($sessions);
    }

    /**
     * Get sessions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSessions()
    {
        return $this->sessions;
    }

    /**
     * Add screens
     *
     * @param \AppBundle\Entity\Screen $screens
     * @return Experiment
     */
    public function addScreen(\AppBundle\Entity\Screen $screens)
    {
        $this->screens[] = $screens;

        return $this;
    }

    /**
     * Remove screens
     *
     * @param \AppBundle\Entity\Screen $screens
     */
    public function removeScreen(\AppBundle\Entity\Screen $screens)
    {
        $this->screens->removeElement($screens);
    }

    /**
     * Get screens
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getScreens()
    {
        return $this->screens;
    }

    /**
     * Set startScreen
     *
     * @param \AppBundle\Entity\Screen $startScreen
     * @return Experiment
     */
    public function setStartScreen(\AppBundle\Entity\Screen $startScreen = null)
    {
        $this->startScreen = $startScreen;

        return $this;
    }

    /**
     * Get startScreen
     *
     * @return \AppBundle\Entity\Screen 
     */
    public function getStartScreen()
    {
        return $this->startScreen;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return Experiment
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
}
