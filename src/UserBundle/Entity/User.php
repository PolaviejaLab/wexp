<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements AdvancedUserInterface, \Serializable
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @ORM\Column(type="string", length=25, unique=true)
	 */
	private $username;
	
	/**
	 * @ORM\Column(type="string", length=64)
	 */
	private $password;
	
	/**
	 * @ORM\Column(type="boolean", name="is_active")
	 */
	private $isActive = 1;

	
	/**
	 * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
	 */
	private $roles;
	
	
	// /////////////////
	// Local functions
	
	
	public function __construct()
	{
		$this->roles = new ArrayCollection();
	}
	
	
	/**
	 * Returns the user ID
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}
	
	
	/**
	 * Sets the username
	 * @param string $username
	 * @return \UserBundle\Entity\User
	 */
	public function setUsername($username)
	{
		$this->username = $username;
		return $this;
	}
	
	
	/**
	 * Sets the salt that was be used to encode the password.
	 * @param NULL $salt
	 * @return \UserBundle\Entity\User
	 */
	public function setSalt($salt)
	{	
		return $this;	
	}
	
	
	/**
	 * Set the password
	 * @param string $password
	 * @return \UserBundle\Entity\User
	 */
	public function setPassword($password)
	{
		$this->password = $password;
		return $this;
	}

	
	/**
	 * Set isActive
	 *
	 * @param boolean $isActive
	 * @return User
	 */
	public function setIsActive($isActive)
	{
		$this->isActive = $isActive;
	
		return $this;
	}
	
	
	/**
	 * Get isActive
	 * @return boolean
	 */
	public function getIsActive()
	{
		return $this->isActive;
	}
	
	
	/**
	 * Add roles
	 * @param \UserBundle\Entity\Role $roles
	 * @return User
	 */
	public function addRole(\UserBundle\Entity\Role $roles)
	{
		$this->roles[] = $roles;
	
		return $this;
	}
	
	
	/**
	 * Remove roles
	 * @param \UserBundle\Entity\Role $roles
	 */
	public function removeRole(\UserBundle\Entity\Role $roles)
	{
		$this->roles->removeElement($roles);
	}
		
	
	// //////////////
    // Serializable

    
    public function serialize()
    {
    	return serialize(array(
    		$this->id, 
    		$this->username, 
    		$this->password,
    		$this->isActive
    	));
    }
    
    
    public function unserialize($serialized)
    {
    	list(
    		$this->id, 
    		$this->username, 
    		$this->password,
    		$this->isActive
    	) = unserialize($serialized);
    }
    
    
    // ///////////////
    // UserInterface


    /**
     * Returns the roles granted to the user.
     */
    public function getRoles()
    {
    	return $this->roles->toArray();
    }
        
    
    /**
     * Returns the password used to authenticate the user.
     * @return string
     */
    public function getPassword()
    {
    	return $this->password;
    }
    
    
    /**
     * Returns the salt that was originally used to encode the password.
     * @return NULL
     */
    public function getSalt()
    {
    	return null;
    }
    
    
    /**
     * Returns the username used to authenticate the user.
     * @return string
     */
    public function getUsername()
    {
    	return $this->username;
    }
    
    
    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
    }
        
    
    // ///////////////////////
    // AdvancedUserInterface
	
	
	/**
	 * Checks whether the user's account has expired.
	 * @return boolean
	 */
    public function isAccountNonExpired()
    {
    	return true;
    }
    
    
    /**
     * Checks whether the user is locked.
     * @return boolean
     */
    public function isAccountNonLocked()
    {
    	return true;
    }
    
    
    /**
     * Checks whether the user's credentials (password) has expired.
     * @return boolean
     */
    public function isCredentialsNonExpired()
    {
    	return true;
    }
    
    
    /**
     * Checks whether the user is enabled.
     */
    public function isEnabled()
    {
    	return $this->getIsActive();
    }
}
