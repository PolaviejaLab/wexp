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
	 * @ORM\Column(type="text")
	 */
	protected $source = "";
	
		
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
    }

    
    /**
     * Set source
     *
     * @param string $source
     * @return Screen
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }
    
    
    public function compileSource()
    {
    	
    }
}
