<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
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
	 * @ORM\Column(type="integer")
	 */
	protected $number_of_actors = 1;
	
	/**
	 * @ORM\Column(type="integer")
	 */	
	protected $matching_script = "";

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
     * Set number_of_actors
     *
     * @param integer $numberOfActors
     * @return Experiment
     */
    public function setNumberOfActors($numberOfActors)
    {
        $this->number_of_actors = $numberOfActors;

        return $this;
    }

    /**
     * Get number_of_actors
     *
     * @return integer 
     */
    public function getNumberOfActors()
    {
        return $this->number_of_actors;
    }

    /**
     * Set matching_script
     *
     * @param integer $matchingScript
     * @return Experiment
     */
    public function setMatchingScript($matchingScript)
    {
        $this->matching_script = $matchingScript;

        return $this;
    }

    /**
     * Get matching_script
     *
     * @return integer 
     */
    public function getMatchingScript()
    {
        return $this->matching_script;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->owners = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get owners
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOwners()
    {
        return $this->owners;
    }
}
