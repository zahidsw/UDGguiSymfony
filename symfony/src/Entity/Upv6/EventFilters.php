<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventFilters
 *
 * @ORM\Table(name="event_filters")
 * @ORM\Entity(repositoryClass="App\Repository\EventFiltersRepository")
 */
class EventFilters
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Actions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $action;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Devices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $device;

    /**
     * @var integer
     *
     * @ORM\Column(name="latency", type="integer", nullable=false)
     */
    private $latency;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_execution", type="datetime", nullable=false)
     */
    private $lastExecution;

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
     * Set latency
     *
     * @param integer $latency
     * @return EventFilters
     */
    public function setLatency($latency)
    {
        $this->latency = $latency;
    
        return $this;
    }

    /**
     * Get latency
     *
     * @return integer 
     */
    public function getLatency()
    {
        return $this->latency;
    }

    /**
     * Set lastExecution
     *
     * @param \DateTime $lastExecution
     * @return EventFilters
     */
    public function setLastExecution($lastExecution)
    {
        $this->lastExecution = $lastExecution;
    
        return $this;
    }

    /**
     * Get lastExecution
     *
     * @return \DateTime 
     */
    public function getLastExecution()
    {
        return $this->lastExecution;
    }

    /**
     * Set action
     *
     * @param \App\Entity\Upv6\Actions $action
     * @return EventFilters
     */
    public function setAction(\App\Entity\Upv6\Actions $action)
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
     * Set device
     *
     * @param \App\Entity\Upv6\Devices $device
     * @return EventFilters
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