<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cards
 *
 * @ORM\Table(name="cards")
 * @ORM\Entity
 */
class Cards
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
     * @ORM\Column(name="ipv6address", type="string", length=39, nullable=false)
     */
    private $ipv6address;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="card_state", type="integer", nullable=true)
     */
    private $cardState;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Protocols", inversedBy="cards")
     * @ORM\JoinTable(name="card_has_protocol",
     * 		joinColumns={@ORM\JoinColumn(name="card_id", referencedColumnName="id")},
     *   	inverseJoinColumns={@ORM\JoinColumn(name="protocol_id", referencedColumnName="id")}
     * 		)
     */
    private $protocols;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->protocols = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set ipv6address
     *
     * @param string $ipv6address
     * @return Cards
     */
    public function setIpv6address($ipv6address)
    {
        $this->ipv6address = $ipv6address;
    
        return $this;
    }

    /**
     * Get ipv6address
     *
     * @return string 
     */
    public function getIpv6address()
    {
        return $this->ipv6address;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Cards
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
     * Set cardState
     *
     * @param integer $cardState
     * @return Cards
     */
    public function setCardState($cardState)
    {
        $this->cardState = $cardState;
    
        return $this;
    }

    /**
     * Get cardState
     *
     * @return integer 
     */
    public function getCardState()
    {
        return $this->cardState;
    }

    /**
     * Add protocol
     *
     * @param \App\Entity\Upv6\Protocols $protocol
     * @return Cards
     */
    public function addProtocol(\App\Entity\Upv6\Protocols $protocol)
    {
        $this->protocols[] = $protocol;
    
        return $this;
    }

    /**
     * Remove protocol
     *
     * @param \App\Entity\Upv6\Protocols $protocol
     */
    public function removeProtocol(\App\Entity\Upv6\Protocols $protocol)
    {
        $this->protocols->removeElement($protocol);
    }

    /**
     * Get protocols
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProtocols()
    {
        return $this->protocols;
    }
}