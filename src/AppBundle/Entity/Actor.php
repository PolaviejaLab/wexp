<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="actor")
 */
class Actor
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ExperimentSession")
	 */
	protected $experiment_session;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ExperimentRole")
	 */
	protected $experiment_role;	

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
     * Set experiment_session
     *
     * @param \AppBundle\Entity\ExperimentSession $experimentSession
     * @return Actor
     */
    public function setExperimentSession(\AppBundle\Entity\ExperimentSession $experimentSession = null)
    {
        $this->experiment_session = $experimentSession;

        return $this;
    }

    /**
     * Get experiment_session
     *
     * @return \AppBundle\Entity\ExperimentSession 
     */
    public function getExperimentSession()
    {
        return $this->experiment_session;
    }

    /**
     * Set experiment_role
     *
     * @param \AppBundle\Entity\ExperimentRole $experimentRole
     * @return Actor
     */
    public function setExperimentRole(\AppBundle\Entity\ExperimentRole $experimentRole = null)
    {
        $this->experiment_role = $experimentRole;

        return $this;
    }

    /**
     * Get experiment_role
     *
     * @return \AppBundle\Entity\ExperimentRole 
     */
    public function getExperimentRole()
    {
        return $this->experiment_role;
    }
}
