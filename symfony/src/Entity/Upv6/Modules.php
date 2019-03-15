<?php

namespace App\Entity\Upv6;


use Doctrine\ORM\Mapping as ORM;

/**
 * Modules
 *
 * @ORM\Table(name="modules")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ModulesRepository")
 */
class Modules
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
     * @ORM\Column(name="version", type="string", length=10, nullable=false)
     */
    private $version;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="released", type="datetime", nullable=true)
     */
    private $released;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor", type="string", length=100, nullable=false)
     */
    private $vendor;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
	
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Upv6\Actions", cascade={"persist"})
     * @ORM\JoinTable(name="module_has_action",
     *      joinColumns={@ORM\JoinColumn(name="module_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="action_id", referencedColumnName="id")}
     *      )
     */
    private $actions;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->actions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Modules
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
     * Set version
     *
     * @param string $version
     * @return Modules
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
     * @return Modules
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
     * Set vendor
     *
     * @param string $vendor
     * @return Modules
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
     * Set description
     *
     * @param string $description
     * @return Modules
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
     * Add actions
     *
     * @param \App\Entity\Upv6\Actions $actions
     * @return Modules
     */
    public function addAction(\App\Entity\Upv6\Actions $actions)
    {
        $this->actions[] = $actions;
    
        return $this;
    }

    /**
     * Remove actions
     *
     * @param \App\Entity\Upv6\Actions $actions
     */
    public function removeAction(\App\Entity\Upv6\Actions $actions)
    {
        $this->actions->removeElement($actions);
    }

    /**
     * Get actions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActions()
    {
        return $this->actions;
    }
}