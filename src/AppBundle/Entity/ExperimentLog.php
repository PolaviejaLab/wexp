<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="experiment_log")
 */
class ExperimentLog
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $timestamp;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ExperimentSession")
	 */
	protected $experiment_session;	
	
	/**
	 * @ORM\ManyToOne(targetEntity="Actor")
	 */	
	protected $actor;
	
	/**
	 * @ORM\Column(type="string", length=254)
	 */
	protected $message;

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
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return ExperimentLog
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return ExperimentLog
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set experiment_session
     *
     * @param \AppBundle\Entity\ExperimentSession $experimentSession
     * @return ExperimentLog
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
     * Set actor
     *
     * @param \AppBundle\Entity\Actor $actor
     * @return ExperimentLog
     */
    public function setActor(\AppBundle\Entity\Actor $actor = null)
    {
        $this->actor = $actor;

        return $this;
    }

    /**
     * Get actor
     *
     * @return \AppBundle\Entity\Actor 
     */
    public function getActor()
    {
        return $this->actor;
    }
}
