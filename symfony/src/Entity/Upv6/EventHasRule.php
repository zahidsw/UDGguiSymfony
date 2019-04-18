<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventHasRule
 *
 * @ORM\Table(name="event_has_rule")
 * @ORM\Entity(repositoryClass="App\Repository\EventHasRuleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EventHasRule
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Actions", fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     */
    private $action;
	
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Devices", fetch="EAGER")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Rules", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rule;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Targets", fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     */
    private $target;

    /**
     * @var boolean
     *
     * @ORM\Column(name="override", type="boolean", nullable=false)
     */
    private $override;

    /**
     * @var boolean
     *
     * @ORM\Column(name="forced", type="boolean", nullable=false)
     */
    private $forced;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var boolean
     *
     * @ORM\Column(name="save_variable", type="boolean", nullable=false)
     */
    private $saveVariable;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_change", type="datetime", nullable=false)
     */
    private $lastChange;

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
     * Set override
     *
     * @param boolean $override
     * @return EventHasRule
     */
    public function setOverride($override)
    {
        $this->override = $override;
    
        return $this;
    }

    /**
     * Get override
     *
     * @return boolean 
     */
    public function getOverride()
    {
        return $this->override;
    }

    /**
     * Set forced
     *
     * @param boolean $forced
     * @return EventHasRule
     */
    public function setForced($forced)
    {
        $this->forced = $forced;
    
        return $this;
    }

    /**
     * Get forced
     *
     * @return boolean 
     */
    public function getForced()
    {
        return $this->forced;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return EventHasRule
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set saveVariable
     *
     * @param boolean $saveVariable
     * @return EventHasRule
     */
    public function setSaveVariable($saveVariable)
    {
        $this->saveVariable = $saveVariable;
    
        return $this;
    }

    /**
     * Get saveVariable
     *
     * @return boolean 
     */
    public function getSaveVariable()
    {
        return $this->saveVariable;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return EventHasRule
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set action
     *
     * @param \App\Entity\Upv6\Actions $action
     * @return EventHasRule
     */
    public function setAction(\App\Entity\Upv6\Actions $action = null)
    {
        $this->action = $action;
    
        return $this;
    }

    /**
     * Get action
     *
     * @return \App\Entity\Upv6\Actions 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set family
     *
     * @param \App\Entity\Upv6\Families $family
     * @return EventHasRule
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
     * @return EventHasRule
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
     * @return EventHasRule
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
     * @return EventHasRule
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
     * @return EventHasRule
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
     * @return EventHasRule
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
     * @return EventHasRule
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

    /**
     * Set rule
     *
     * @param \App\Entity\Upv6\Rules $rule
     * @return EventHasRule
     */
    public function setRule(\App\Entity\Upv6\Rules $rule)
    {
        $this->rule = $rule;
    
        return $this;
    }

    /**
     * Get rule
     *
     * @return \App\Entity\Upv6\Rules 
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * Set target
     *
     * @param \App\Entity\Upv6\Targets $target
     * @return EventHasRule
     */
    public function setTarget(\App\Entity\Upv6\Targets $target = null)
    {
        $this->target = $target;
    
        return $this;
    }

    /**
     * Get target
     *
     * @return \App\Entity\Upv6\Targets 
     */
    public function getTarget()
    {
        return $this->target;
    }
    
    /**
     * Set lastChange
     *
     * @param \DateTime $lastChange
     * @return EventHasRule
     */
    public function setLastChange($lastChange)
    {
    	$this->lastChange = $lastChange;
    
    	return $this;
    }
    
    /**
     * Get lastChange
     *
     * @return \DateTime
     */
    public function getLastChange()
    {
    	return $this->lastChange;
    }
    
    /**
     * @ORM\PreFlush()
     */
    public function updateLastChangeDate()
    {
    	$this->setLastChange(new \Datetime());
    }
}