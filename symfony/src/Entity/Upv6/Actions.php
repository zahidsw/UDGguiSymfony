<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actions
 *
 * @ORM\Table(name="actions")
 * @ORM\Entity
 */
class Actions
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=30, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="internal_name", type="string", length=100, nullable=false)
     */
    private $internalName;

    /**
     * @var integer
     *
     * @ORM\Column(name="kind", type="integer", nullable=false)
     */
    private $kind;
    
    /**
     * @var string
     *
     * @ORM\Column(name="image_name", type="string", length=255, nullable=true)
     */
    private $imageName;
    
    /**
 * @var integer
     *
     * @ORM\Column(name="data_format", type="integer", nullable=false)
     */
    private $dataFormat;
	
	/**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=30, nullable=true)
     */
    private $unit;
	
    /**   
      * @ORM\OneToMany(targetEntity="App\Entity\Upv6\Parameters", mappedBy="action")
     */
    private $parameters;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parameters = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set imageName
     *
     * @param string $imageName
     * @return Actions
     */
    public function setImageName($imageName)
    {
    	$this->imageName = $imageName;
    
    	return $this;
    }
    
    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName()
    {
    	return $this->imageName;
    }

    /**
     * Set internalName
     *
     * @param string $internalName
     * @return Actions
     */
    public function setInternalName($internalName)
    {
        $this->internalName = $internalName;
    
        return $this;
    }

    /**
     * Get internalName
     *
     * @return string 
     */
    public function getInternalName()
    {
        return $this->internalName;
    }

    /**
     * Set kind
     *
     * @param integer $kind
     * @return Actions
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
     * Set dataFormat
     *
     * @param integer $dataFormat
     * @return Actions
     */
    public function setDataFormat($dataFormat)
    {
        $this->dataFormat = $dataFormat;
    
        return $this;
    }

    /**
     * Get dataFormat
     *
     * @return integer 
     */
    public function getDataFormat()
    {
        return $this->dataFormat;
    }
	
	/**
     * Set unit
     *
     * @param string $unit
     * @return Actions
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    
        return $this;
    }

    /**
     * Get unit
     *
     * @return string 
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Add parameters
     *
     * @param \App\Entity\Upv6\Parameters $parameters
     * @return Actions
     */
    public function addParameter(\App\Entity\Upv6\Parameters $parameters)
    {
        $this->parameters[] = $parameters;
    
        return $this;
    }

    /**
     * Remove parameters
     *
     * @param \App\Entity\Upv6\Parameters $parameters
     */
    public function removeParameter(\App\Entity\Upv6\Parameters $parameters)
    {
        $this->parameters->removeElement($parameters);
    }

    /**
     * Get parameters
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParameters()
    {
        return $this->parameters;
    }
    
    public static function cmp_ActionsName(Actions $a, Actions $b)
    {
    	$al = strtolower($a->getInternalName());
    	$bl = strtolower($b->getInternalName());
    	
    	if ($al == $bl) {
    		return 0;
    	}
    	
    	return ($al > $bl) ? +1 : -1;
    }
}
