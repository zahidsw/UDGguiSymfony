<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * SystemLogs
 *
 * @ORM\Table(name="system_logs")
 * @ORM\Entity
 */
class SystemLogs
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="short_message", type="text", nullable=false)
     */
    private $shortMessage;

    /**
     * @var string
     *
     * @ORM\Column(name="long_message", type="text", nullable=false)
     */
    private $longMessage;
	
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Devices", fetch="LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    private $device;

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return SystemLogs
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set shortMessage
     *
     * @param string $shortMessage
     * @return SystemLogs
     */
    public function setShortMessage($shortMessage)
    {
        $this->shortMessage = $shortMessage;
    
        return $this;
    }

    /**
     * Get shortMessage
     *
     * @return string 
     */
    public function getShortMessage()
    {
        return $this->shortMessage;
    }

    /**
     * Set longMessage
     *
     * @param string $longMessage
     * @return SystemLogs
     */
    public function setLongMessage($longMessage)
    {
        $this->longMessage = $longMessage;
    
        return $this;
    }

    /**
     * Get longMessage
     *
     * @return string 
     */
    public function getLongMessage()
    {
        return $this->longMessage;
    }

    /**
     * Set device
     *
     * @param \App\Entity\Upv6\Devices $device
     * @return SystemLogs
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