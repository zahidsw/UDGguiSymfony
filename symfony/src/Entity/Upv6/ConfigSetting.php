<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConfigSetting
 *
 * @ORM\Table(name="config_settings")
 * @ORM\Entity
 */
class ConfigSetting
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
     * @var \ConfigSet
     *
     * @ORM\ManyToOne(targetEntity="ConfigSet", inversedBy="settings")
     * @ORM\JoinColumn(name="config_set_id", referencedColumnName="id", nullable=false)
     */
    private $configSet;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=false)
     */
    private $value;

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
     * @return ConfigSetting
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
     * Set value
     *
     * @param string $value
     * @return ConfigSetting
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
     * Set configSet
     *
     * @param \App\Entity\Upv6\ConfigSet $configSet
     * @return ConfigSetting
     */
    public function setConfigSet(\App\Entity\Upv6\ConfigSet $configSet)
    {
        $this->configSet = $configSet;
    
        return $this;
    }

    /**
     * Get configSet
     *
     * @return \App\Entity\Upv6\ConfigSet 
     */
    public function getConfigSet()
    {
        return $this->configSet;
    }
}