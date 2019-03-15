<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * Protocols
 *
 * @ORM\Table(name="protocols")
 * @ORM\Entity
 */
class Protocols
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
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
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=10, nullable=false)
     */
    private $version;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="released", type="datetime", nullable=false)
     */
    private $released;

    /**
     * @var integer
     *
     * @ORM\Column(name="default_tcp_port", type="integer", nullable=false)
     */
    private $defaultTcpPort;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_name", type="string", length=255, nullable=false)
     */
    private $iconName;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * 
     * @ORM\ManyToMany(targetEntity="Cards", mappedBy="protocols")
     */
    private $cards;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cards = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Protocols
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
     * @return Protocols
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
     * Set version
     *
     * @param string $version
     * @return Protocols
     */
    public function setVersion($version)
    {
        $this->version = $version;
    
        return $this;
    }

    /**
     * Get version
     *
     * @return string 
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set released
     *
     * @param \DateTime $released
     * @return Protocols
     */
    public function setReleased($released)
    {
        $this->released = $released;
    
        return $this;
    }

    /**
     * Get released
     *
     * @return \DateTime 
     */
    public function getReleased()
    {
        return $this->released;
    }

    /**
     * Set defaultTcpPort
     *
     * @param integer $defaultTcpPort
     * @return Protocols
     */
    public function setDefaultTcpPort($defaultTcpPort)
    {
        $this->defaultTcpPort = $defaultTcpPort;
    
        return $this;
    }

    /**
     * Get defaultTcpPort
     *
     * @return integer 
     */
    public function getDefaultTcpPort()
    {
        return $this->defaultTcpPort;
    }

    /**
     * Set iconName
     *
     * @param string $iconName
     * @return Protocols
     */
    public function setIconName($iconName)
    {
        $this->iconName = $iconName;
    
        return $this;
    }

    /**
     * Get iconName
     *
     * @return string 
     */
    public function getIconName()
    {
        return $this->iconName;
    }

    /**
     * Add card
     *
     * @param \App\Entity\Upv6\Cards $card
     * @return Protocols
     */
    public function addCard(\App\Entity\Upv6\Cards $card)
    {
        $this->cards[] = $card;
    
        return $this;
    }

    /**
     * Remove card
     *
     * @param \App\Entity\Upv6\Cards $card
     */
    public function removeCard(\App\Entity\Upv6\Cards $card)
    {
        $this->cards->removeElement($card);
    }

    /**
     * Get cards
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCards()
    {
        return $this->cards;
    }
}