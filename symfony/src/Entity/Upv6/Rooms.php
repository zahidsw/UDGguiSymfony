<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rooms
 *
 * @ORM\Table(name="rooms")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\RoomsRepository")
 */
class Rooms
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Floors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $floor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\RoomTypes", inversedBy="rooms")
     * @ORM\JoinColumn(name="room_type_id", nullable=false)
     */
    private $roomType;

    /**
     * @var integer
     *
     * @ORM\Column(name="room_state", type="integer", nullable=false)
     */
    private $roomState;

    /**
     * @var integer
     *
     * @ORM\Column(name="importance_level", type="integer", nullable=false)
     */
    private $importanceLevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="energy_level", type="integer", nullable=false)
     */
    private $energyLevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="esp_id", type="integer", nullable=false)
     */
    private $espId;

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
     * @return Rooms
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
     * Set roomState
     *
     * @param integer $roomState
     * @return Rooms
     */
    public function setRoomState($roomState)
    {
        $this->roomState = $roomState;
    
        return $this;
    }

    /**
     * Get roomState
     *
     * @return integer 
     */
    public function getRoomState()
    {
        return $this->roomState;
    }

    /**
     * Set importanceLevel
     *
     * @param integer $importanceLevel
     * @return Rooms
     */
    public function setImportanceLevel($importanceLevel)
    {
        $this->importanceLevel = $importanceLevel;
    
        return $this;
    }

    /**
     * Get importanceLevel
     *
     * @return integer 
     */
    public function getImportanceLevel()
    {
        return $this->importanceLevel;
    }

    /**
     * Set energyLevel
     *
     * @param integer $energyLevel
     * @return Rooms
     */
    public function setEnergyLevel($energyLevel)
    {
        $this->energyLevel = $energyLevel;
    
        return $this;
    }

    /**
     * Get energyLevel
     *
     * @return integer 
     */
    public function getEnergyLevel()
    {
        return $this->energyLevel;
    }

    /**
     * Set espId
     *
     * @param integer $espId
     * @return Rooms
     */
    public function setEspId($espId)
    {
        $this->espId = $espId;
    
        return $this;
    }

    /**
     * Get espId
     *
     * @return integer 
     */
    public function getEspId()
    {
        return $this->espId;
    }

    /**
     * Set floor
     *
     * @param \App\Entity\Upv6\Floors $floor
     * @return Rooms
     */
    public function setFloor(\App\Entity\Upv6\Floors $floor)
    {
        $this->floor = $floor;
    
        return $this;
    }

    /**
     * Get floor
     *
     * @return \App\Entity\Upv6\Floors 
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * Set roomType
     *
     * @param \App\Entity\Upv6\RoomTypes $roomType
     * @return Rooms
     */
    public function setRoomType(\App\Entity\Upv6\RoomTypes $roomType)
    {
        $this->roomType = $roomType;
    
        return $this;
    }

    /**
     * Get roomType
     *
     * @return \App\Entity\Upv6\RoomTypes 
     */
    public function getRoomType()
    {
        return $this->roomType;
    }
}