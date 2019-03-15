<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * GroupHasEntity
 *
 * @ORM\Table(name="group_has_entity")
 * @ORM\Entity
 */
class GroupHasEntity
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
     * @var integer
     *
     * @ORM\Column(name="entity_id", type="integer", nullable=false)
     */
    private $entityId;

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
     * @var integer
     *
     * @ORM\Column(name="access_profile", type="integer", nullable=true)
     */
    private $accessProfile;

    /**
     * @var \Groups
     *
     * @ORM\ManyToOne(targetEntity="Groups", inversedBy="entities")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="group_id", nullable=false)
     */
    private $group;

	public function __construct()
	{
		$this->setCreationDate(new \DateTime());
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
     * Set entityId
     *
     * @param integer $entityId
     * @return GroupHasEntity
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
    
        return $this;
    }

    /**
     * Get entityId
     *
     * @return integer 
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return GroupHasEntity
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
     * @return GroupHasEntity
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
     * Set accessProfile
     *
     * @param integer $accessProfile
     * @return GroupHasEntity
     */
    public function setAccessProfile($accessProfile)
    {
        $this->accessProfile = $accessProfile;
    
        return $this;
    }

    /**
     * Get accessProfile
     *
     * @return integer 
     */
    public function getAccessProfile()
    {
        return $this->accessProfile;
    }

    /**
     * Set group
     *
     * @param \App\Entity\Upv6\Groups $group
     * @return GroupHasEntity
     */
    public function setGroup(\App\Entity\Upv6\Groups $group = null)
    {
        $this->group = $group;
    
        return $this;
    }

    /**
     * Get group
     *
     * @return \App\Entity\Upv6\Groups 
     */
    public function getGroup()
    {
        return $this->group;
    }
}