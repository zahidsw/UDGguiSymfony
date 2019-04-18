<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * Targets
 *
 * @ORM\Table(name="targets")
 * @ORM\Entity
 */
class Targets
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Families")
     * @ORM\JoinColumn(nullable=true)
     */
    private $family;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Categories")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Devices")
     * @ORM\JoinColumn(nullable=true)
     */
    private $device;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\RoomTypes")
     * @ORM\JoinColumn(name="room_type_id", nullable=true)
     */
    private $roomType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Rooms")
     * @ORM\JoinColumn(nullable=true)
     */
    private $room;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Floors")
     * @ORM\JoinColumn(nullable=true)
     */
    private $floor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Buildings")
     * @ORM\JoinColumn(nullable=true)
     */
    private $building;

    /**
     * @var string
     *
     * @ORM\Column(name="target_name", type="string", length=255, nullable=false)
     */
    private $targetName;

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
     * Set targetName
     *
     * @param string $targetName
     * @return Targets
     */
    public function setTargetName($targetName)
    {
        $this->targetName = $targetName;
    
        return $this;
    }

    /**
     * Get targetName
     *
     * @return string 
     */
    public function getTargetName()
    {
        return $this->targetName;
    }

    /**
     * Set family
     *
     * @param \App\Entity\Upv6\Families $family
     * @return Targets
     */
    public function setFamily(\App\Entity\Upv6\Families $family = null)
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
    
    /**
     * Set category
     *
     * @param \App\Entity\Upv6\Categories $category
     * @return Targets
     */
    public function setCategory(\App\Entity\Upv6\Categories $category = null)
    {
    	$this->category = $category;
    
    	return $this;
    }
    
    /**
     * Get category
     *
     * @return \App\Entity\Upv6\Categories
     */
    public function getCategory()
    {
    	return $this->category;
    }

    /**
     * Set device
     *
     * @param \App\Entity\Upv6\Devices $device
     * @return Targets
     */
    public function setDevice(\App\Entity\Upv6\Devices $device = null)
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

    /**
     * Set roomType
     *
     * @param \App\Entity\Upv6\RoomTypes $roomType
     * @return Targets
     */
    public function setRoomType(\App\Entity\Upv6\RoomTypes $roomType = null)
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

    /**
     * Set room
     *
     * @param \App\Entity\Upv6\Rooms $room
     * @return Targets
     */
    public function setRoom(\App\Entity\Upv6\Rooms $room = null)
    {
        $this->room = $room;
    
        return $this;
    }

    /**
     * Get room
     *
     * @return \App\Entity\Upv6\Rooms 
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Set floor
     *
     * @param \App\Entity\Upv6\Floors $floor
     * @return Targets
     */
    public function setFloor(\App\Entity\Upv6\Floors $floor = null)
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
     * Set building
     *
     * @param \App\Entity\Upv6\Buildings $building
     * @return Targets
     */
    public function setBuilding(\App\Entity\Upv6\Buildings $building = null)
    {
        $this->building = $building;
    
        return $this;
    }

    /**
     * Get building
     *
     * @return \App\Entity\Upv6\Buildings 
     */
    public function getBuilding()
    {
        return $this->building;
    }
}