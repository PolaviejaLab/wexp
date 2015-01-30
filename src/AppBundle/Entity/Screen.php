<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="screen")
 */
class Screen
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
	 * @ORM\Column(type="string")
	 */
	protected $name = "";
	
	/**
	 * @ORM\OneToMany(targetEntity="ScreenVersion", mappedBy="screen")
	 */
	protected $screenVersions;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ScreenVersion")
	 */
	protected $currentVersion;
		
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
     * @return Screen
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
     * Set experiment
     *
     * @param \AppBundle\Entity\Experiment $experiment
     * @return Screen
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
        $this->screenVersions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add screenVersions
     *
     * @param \AppBundle\Entity\ScreenVersion $screenVersions
     * @return Screen
     */
    public function addScreenVersion(\AppBundle\Entity\ScreenVersion $screenVersions)
    {
        $this->screenVersions[] = $screenVersions;

        return $this;
    }

    /**
     * Remove screenVersions
     *
     * @param \AppBundle\Entity\ScreenVersion $screenVersions
     */
    public function removeScreenVersion(\AppBundle\Entity\ScreenVersion $screenVersions)
    {
        $this->screenVersions->removeElement($screenVersions);
    }

    /**
     * Get screenVersions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getScreenVersions()
    {
        return $this->screenVersions;
    }
        

    /**
     * Set currentVersion
     *
     * @param \AppBundle\Entity\ScreenVersion $currentVersion
     * @return Screen
     */
    public function setCurrentVersion(\AppBundle\Entity\ScreenVersion $currentVersion = null)
    {
        $this->currentVersion = $currentVersion;

        return $this;
    }

    /**
     * Get currentVersion
     *
     * @return \AppBundle\Entity\ScreenVersion 
     */
    public function getCurrentVersion()
    {
        return $this->currentVersion;
    }
}
