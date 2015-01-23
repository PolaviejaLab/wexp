<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="experiment_session")
 */
class Session
{
	public static $STATUS_AWAITING_ACTORS = 0;
	public static $STATUS_RUNNING = 1;
	public static $STATUS_FINISHED = 2;


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
	 * @ORM\OneToMany(targetEntity="Player", mappedBy="session") 
	 */
	protected $players;
	
	/**
	 * @ORM\Column(type="integer", nullable=false)
	 */
	protected $status = 0;
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */	
	protected $started;
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $stopped;
	

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
     * Set status
     *
     * @param integer $status
     * @return ExperimentSession
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set started
     *
     * @param \DateTime $started
     * @return ExperimentSession
     */
    public function setStarted($started)
    {
        $this->started = $started;

        return $this;
    }

    /**
     * Get started
     *
     * @return \DateTime 
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * Set stopped
     *
     * @param \DateTime $stopped
     * @return ExperimentSession
     */
    public function setStopped($stopped)
    {
        $this->stopped = $stopped;

        return $this;
    }

    /**
     * Get stopped
     *
     * @return \DateTime 
     */
    public function getStopped()
    {
        return $this->stopped;
    }

    /**
     * Set experiment
     *
     * @param \AppBundle\Entity\Experiment $experiment
     * @return ExperimentSession
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
     * Constructor
     */
    public function __construct()
    {
        $this->players = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add players
     *
     * @param \AppBundle\Entity\Player $players
     * @return Session
     */
    public function addPlayer(\AppBundle\Entity\Player $players)
    {
        $this->players[] = $players;

        return $this;
    }

    /**
     * Remove players
     *
     * @param \AppBundle\Entity\Player $players
     */
    public function removePlayer(\AppBundle\Entity\Player $players)
    {
        $this->players->removeElement($players);
    }

    /**
     * Get players
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlayers()
    {
        return $this->players;
    }
}
