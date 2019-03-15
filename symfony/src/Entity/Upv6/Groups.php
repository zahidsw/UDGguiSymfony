<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Groups
 *
 * @ORM\Table(name="groups")
 * @ORM\Entity
 */
class Groups
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="group_id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="group_name", type="text", nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="group_category", type="integer", nullable=false)
     */
    private $category;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deletion_date", type="datetime", nullable=true)
     */
    private $deletionDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;
    
    /**
     * @ORM\OneToMany(targetEntity="GroupHasEntity", mappedBy="group")
     */
    private $entities;

    public function __construct()
    {
    	$this->setCreationDate(new \DateTime());
    	$this->entities = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Groups
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
     * Set category
     *
     * @param integer $category
     * @return Group
     */
    public function setCategory($category)
    {
    	$this->category = $category;
    
    	return $this;
    }
    
    /**
     * Get category
     *
     * @return integer
     */
    public function getCategory()
    {
    	return $this->category;
    }
    
    /**
     * Set description
     *
     * @param string $description
     * @return Groups
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Groups
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
     * Set deletionDate
     *
     * @param \DateTime $deletionDate
     * @return Groups
     */
    public function setDeletionDate($deletionDate)
    {
        $this->deletionDate = $deletionDate;
    
        return $this;
    }

    /**
     * Get deletionDate
     *
     * @return \DateTime 
     */
    public function getDeletionDate()
    {
        return $this->deletionDate;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Groups
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Add entities
     *
     * @param \App\Entity\Upv6\GroupHasEntity $entities
     * @return Groups
     */
    public function addEntitie(\App\Entity\Upv6\GroupHasEntity $entities)
    {
        $this->entities[] = $entities;
    
        return $this;
    }

    /**
     * Remove entitie
     *
     * @param \App\Entity\Upv6\GroupHasEntity $entities
     */
    public function removeEntitie(\App\Entity\Upv6\GroupHasEntity $entities)
    {
        $this->entities->removeElement($entities);
    }
    
    /**
     * Clear all entities
     *
     */
    public function clearEntities()
    {
    	$this->entities->clear();
    }

    /**
     * Get entities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEntities()
    {
        return $this->entities;
    }
}