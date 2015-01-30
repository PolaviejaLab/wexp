<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="screen_version")
 */
class ScreenVersion
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="datetime", nullable=false)
	 */
	protected $timestamp;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Screen")
	 */
	protected $screen = "";

	/**
	 * @ORM\Column(type="text", nullable=false)
	 */
	protected $contents = "";
	
	/**
	 * @ORM\OneToMany(targetEntity="ScreenVersion", mappedBy="screen")
	 */
	protected $screenVersions;
	
	
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
     * @return ScreenVersion
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
     * Set contents
     *
     * @param string $contents
     * @return ScreenVersion
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * Get contents
     *
     * @return string 
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Set screen
     *
     * @param \AppBundle\Entity\Screen $screen
     * @return ScreenVersion
     */
    public function setScreen(\AppBundle\Entity\Screen $screen = null)
    {
        $this->screen = $screen;

        return $this;
    }

    /**
     * Get screen
     *
     * @return \AppBundle\Entity\Screen 
     */
    public function getScreen()
    {
        return $this->screen;
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
     * @return ScreenVersion
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
}
