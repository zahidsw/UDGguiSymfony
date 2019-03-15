<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * Variables
 *
 * @ORM\Table(name="variables")
 * @ORM\Entity
 */
class Variables
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Devices", inversedBy="variables", fetch="LAZY")
     */
    private $device;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="serialized_value", type="blob", nullable=false)
     */
    private $serializedValue;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=255, nullable=true)
     */
    private $unit;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="string_value", type="text", nullable=false)
     */
    private $stringValue;



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
     * @return Variables
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
     * Set serializedValue
     *
     * @param string $serializedValue
     * @return Variables
     */
    public function setSerializedValue($serializedValue)
    {
        $this->serializedValue = $serializedValue;
    
        return $this;
    }

    /**
     * Get serializedValue
     *
     * @return string 
     */
    public function getSerializedValue()
    {
        return $this->serializedValue;
    }

    /**
     * Set unit
     *
     * @param string $unit
     * @return Variables
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    
        return $this;
    }

    /**
     * Get unit
     *
     * @return string 
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Variables
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set stringValue
     *
     * @param string $stringValue
     * @return Variables
     */
    public function setStringValue($stringValue)
    {
        $this->stringValue = $stringValue;
    
        return $this;
    }

    /**
     * Get stringValue
     *
     * @return string 
     */
    public function getStringValue()
    {
        return $this->stringValue;
    }

    /**
     * Set device
     *
     * @param \App\Entity\Upv6\Devices $device
     * @return Variables
     */
    public function setDevice(\App\Entity\Upv6\Devices $device)
    {
        $this->device = $device;
    
        return $this;
    }

    /**
     * Get device
     *
     * @return \App\Entity\Upv6\Devices 
     */
    public function getDevice()
    {
        return $this->device;
    }
}