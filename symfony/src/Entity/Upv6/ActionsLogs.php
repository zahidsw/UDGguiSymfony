<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * SystemLogs
 *
 * @ORM\Table(name="actions_log")
 * @ORM\Entity
 */
class ActionsLogs
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
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiration_date", type="datetime", nullable=false)
     */
    private $expirationDate;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer", nullable=false)
     */
    private $priority;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Devices", fetch="LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    private $device;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Actions", fetch="LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    private $action;

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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return ActionsLogs
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    
        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set expirationDate
     *
     * @param \DateTime $expirationDate
     * @return ActionsLogs
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
    
        return $this;
    }

    /**
     * Get expirationDate
     *
     * @return \DateTime 
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return ActionsLogs
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    
        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set device
     *
     * @param \App\Entity\Upv6\Devices $device
     * @return ActionsLogs
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

    /**
     * Set action
     *
     * @param \App\Entity\Upv6\Actions $action
     * @return ActionsLogs
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
}