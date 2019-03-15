<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * Models
 *
 * @ORM\Table(name="models")
 * @ORM\Entity
 */
class Models
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor", type="string", length=100, nullable=true)
     */
    private $vendor;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Modules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $module;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Families")
     * @ORM\JoinColumn(nullable=false)
     */
    private $family;



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
     * @return Models
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
     * Set description
     *
     * @param string $description
     * @return Models
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set vendor
     *
     * @param string $vendor
     * @return Models
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
    
        return $this;
    }

    /**
     * Get vendor
     *
     * @return string 
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * Set moduleId
     *
     * @param string $moduleId
     * @return Models
     */
    public function setModuleId($moduleId)
    {
        $this->moduleId = $moduleId;
    
        return $this;
    }

    /**
     * Get moduleId
     *
     * @return string 
     */
    public function getModuleId()
    {
        return $this->moduleId;
    }

    /**
     * Set familyId
     *
     * @param integer $familyId
     * @return Models
     */
    public function setFamilyId($familyId)
    {
        $this->familyId = $familyId;
    
        return $this;
    }

    /**
     * Get familyId
     *
     * @return integer 
     */
    public function getFamilyId()
    {
        return $this->familyId;
    }

    /**
     * Set module
     *
     * @param \App\Entity\Upv6\Modules $module
     * @return Models
     */
    public function setModule(\App\Entity\Upv6\Modules $module)
    {
        $this->module = $module;
    
        return $this;
    }

    /**
     * Get module
     *
     * @return \App\Entity\Upv6\Modules 
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set family
     *
     * @param \App\Entity\Upv6\Families $family
     * @return Models
     */
    public function setFamily(\App\Entity\Upv6\Families $family)
    {
        $this->family = $family;
    
        return $this;
    }

    /**
     * Get family
     *
     * @return \App\Entity\Upv6\Families 
     */
    public function getFamily()
    {
        return $this->family;
    }
}