<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parameters
 *
 * @ORM\Table(name="parameters")
 * @ORM\Entity
 */
class Parameters
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
     * @var boolean
     *
     * @ORM\Column(name="is_return", type="boolean", nullable=false)
     */
    private $isReturn;

    /**
     * @var integer
     *
     * @ORM\Column(name="kind", type="integer", nullable=false)
     */
    private $kind;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer", nullable=false)
     */
    private $position;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Actions", inversedBy="parameters")
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
     * Set isReturn
     *
     * @param boolean $isReturn
     * @return Parameters
     */
    public function setIsReturn($isReturn)
    {
        $this->isReturn = $isReturn;
    
        return $this;
    }

    /**
     * Get isReturn
     *
     * @return boolean 
     */
    public function getIsReturn()
    {
        return $this->isReturn;
    }

    /**
     * Set kind
     *
     * @param integer $kind
     * @return Parameters
     */
    public function setKind($kind)
    {
        $this->kind = $kind;
    
        return $this;
    }

    /**
     * Get kind
     *
     * @return integer 
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Parameters
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
     * Set position
     *
     * @param integer $position
     * @return Parameters
     */
    public function setPosition($position)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set action
     *
     * @param \App\Entity\Upv6\Actions $action
     * @return Parameters
     */
    public function setAction(\iot6\InteractBundle\Entity\Actions $action)
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