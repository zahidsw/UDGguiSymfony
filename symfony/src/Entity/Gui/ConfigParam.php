<?php

namespace App\Entity\Gui;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConfigParam
 *
 * @ORM\Table(name="config_param")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ConfigParamRepository")
 */
class ConfigParam
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Gui\ConfigParamCat", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $paramCategorie;
    
    /**
     * @var string
     *
     * @ORM\Column(name="param", type="string", length=255, nullable=false)
     */
    private $param;
    
    /**
     * @var string
     *
     * @ORM\Column(name="default_value", type="string", length=255, nullable=false)
     */
    private $defaultValue;

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
     * Set param
     *
     * @param string $param
     * @return ConfigParam
     */
    public function setParam($param)
    {
        $this->param = $param;
    
        return $this;
    }

    /**
     * Get param
     *
     * @return string 
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * Set defaultValue
     *
     * @param string $defaultValue
     * @return ConfigParam
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    
        return $this;
    }

    /**
     * Get defaultValue
     *
     * @return string 
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Set paramCategorie
     *
     * @param \App\Entity\Gui\ConfigParamCat $paramCategorie
     * @return ConfigParam
     */
    public function setParamCategorie(\App\Entity\Gui\ConfigParamCat $paramCategorie)
    {
        $this->paramCategorie = $paramCategorie;
    
        return $this;
    }

    /**
     * Get paramCategorie
     *
     * @return \App\Entity\Gui\ConfigParamCat 
     */
    public function getParamCategorie()
    {
        return $this->paramCategorie;
    }
}