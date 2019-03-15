<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreFlush;

/**
 * Rules
 *
 * @ORM\Table(name="rules")
 * @ORM\Entity(repositoryClass="App\Repository\RulesRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Rules
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="rule", type="text", nullable=false)
     */
    private $rule;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @var integer
     *
     * @ORM\Column(name="rule_type", type="integer", nullable=false)
     */
    private $ruleType;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_name", type="string", length=255, nullable=true)
     */
    private $iconName;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority_level", type="integer", nullable=false)
     */
    private $priorityLevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

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
     * @return Rules
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
     * Set rule
     *
     * @param string $rule
     * @return Rules
     */
    public function setRule($rule)
    {
        $this->rule = $rule;
    
        return $this;
    }

    /**
     * Get rule
     *
     * @return string 
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Rules
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
     * Set ruleType
     *
     * @param integer $ruleType
     * @return Rules
     */
    public function setRuleType($ruleType)
    {
        $this->ruleType = $ruleType;
    
        return $this;
    }

    /**
     * Get ruleType
     *
     * @return integer 
     */
    public function getRuleType()
    {
        return $this->ruleType;
    }

    /**
     * Set iconName
     *
     * @param string $iconName
     * @return Rules
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
     * Set comment
     *
     * @param string $comment
     * @return Rules
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Rules
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set priorityLevel
     *
     * @param integer $priorityLevel
     * @return Rules
     */
    public function setPriorityLevel($priorityLevel)
    {
        $this->priorityLevel = $priorityLevel;
    
        return $this;
    }

    /**
     * Get priorityLevel
     *
     * @return integer 
     */
    public function getPriorityLevel()
    {
        return $this->priorityLevel;
    }

    /**
     * Set action
     *
     * @param \App\Entity\Upv6\Actions $action
     * @return Rules
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
     * @ORM\PreFlush()
     */
    public function updateLastChangeDate()
    {
    	$this->setUpdatedAt(new \Datetime());
    }

    /**
     * Set user ID
     *
     * @param integer $userId
     * @return Rules
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    
        return $this;
    }

    /**
     * Get user ID
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
