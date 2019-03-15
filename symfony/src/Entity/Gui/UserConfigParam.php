<?php

namespace App\Entity\Gui;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table(name="user_config_param")
 * @ORM\Entity()
 */
class UserConfigParam
{
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="App\Entity\Gui\User")
	 */
	private $user;
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="App\Entity\Gui\ConfigParam")
	 */
	private $configParam;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="value", type="string", length=255)
	 */
	private $value;

    /**
     * Set value
     *
     * @param string $value
     * @return UserConfigParam
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set user
     *
     * @param \App\Entity\Gui\User $user
     * @return UserConfigParam
     */
    public function setUser(\App\Entity\Gui\User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \App\Entity\Gui\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set configParam
     *
     * @param \App\Entity\Gui\ConfigParam $configParam
     * @return UserConfigParam
     */
    public function setConfigParam(\App\Entity\Gui\ConfigParam $configParam)
    {
        $this->configParam = $configParam;
    
        return $this;
    }

    /**
     * Get configParam
     *
     * @return \App\Entity\Gui\ConfigParam 
     */
    public function getConfigParam()
    {
        return $this->configParam;
    }
}