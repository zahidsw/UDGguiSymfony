<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConfigSet
 *
 * @ORM\Table(name="config_set")
 * @ORM\Entity(repositoryClass="App\Repository\ConfigSetRepository")
 */
class ConfigSet
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
	
    /**
     * @var \App\Entity\Upv6\UsersMiddleware
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\UsersMiddleware")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="active_config", type="boolean", nullable=false)
     */
    private $active;
    
    /**
     * @ORM\OneToMany(targetEntity="ConfigSetting", mappedBy="configSet")
     */
    private $settings;

    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->settings = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * @return ConfigSet
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
     * Set active
     *
     * @param boolean $active
     * @return ConfigSet
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set user
     *
     * @param \App\Entity\Upv6\UsersMiddleware $user
     * @return ConfigSet
     */
    public function setUser(\App\Entity\Upv6\UsersMiddleware $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \App\Entity\Upv6\UsersMiddleware 
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Add settings
     *
     * @param \App\Entity\Upv6\ConfigSetting $settings
     * @return ConfigSet
     */
    public function addSetting(\App\Entity\Upv6\ConfigSetting $settings)
    {
        $this->settings[] = $settings;
    
        return $this;
    }

    /**
     * Remove settings
     *
     * @param \App\Entity\Upv6\ConfigSetting $settings
     */
    public function removeSetting(\App\Entity\Upv6\ConfigSetting $settings)
    {
        $this->settings->removeElement($settings);
    }

    /**
     * Get settings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSettings()
    {
        return $this->settings;
    }
}