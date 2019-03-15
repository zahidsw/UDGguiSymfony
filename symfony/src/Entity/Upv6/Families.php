<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * Families
 *
 * @ORM\Table(name="families")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\FamiliesRepository")
 */
class Families
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
     * @ORM\Column(name="internal_name", type="string", length=100, nullable=false)
     */
    private $internalName;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_name", type="string", length=45, nullable=true)
     */
    private $iconName;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Upv6\Devices", mappedBy="family")
     */
    private $devices;
    
    private $file;

	/**
     * @ORM\ManyToMany(targetEntity="App\Entity\Upv6\Actions", cascade={"persist"})
     * @ORM\JoinTable(name="family_has_action",
     *      joinColumns={@ORM\JoinColumn(name="family_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="action_id", referencedColumnName="id")}
     *      )
     */
    private $actions;

    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->devices = new \Doctrine\Common\Collections\ArrayCollection();
		$this->actions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set internalName
     *
     * @param string $internalName
     * @return Families
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
     * Set iconName
     *
     * @param string $iconName
     * @return Families
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
     * Set file
     *
     * @param string $file
     * @return Categories
     */
    public function setFile($file)
    {
    	$this->file = $file;
    
    	return $this;
    }
    
    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
    	return $this->file;
    }
    
    public function upload()
    {
    	// Si jamais il n'y a pas de fichier (champ facultatif)
    	if (null === $this->file) {
    		return;
    	}
    
    	// On garde le nom original du fichier de l'internaute
    	$name = $this->file->getClientOriginalName();
    
    	// On d�place le fichier envoy� dans le r�pertoire de notre choix
    	$this->file->move($this->getUploadRootDir(), $name);
    
    	// On sauvegarde le nom de fichier dans notre attribut $url
    	$this->iconName = $name;
    }
    
    public function getUploadDir()
    {
    	// On retourne le chemin relatif vers l'image pour un navigateur
    	return 'images/families';
    }
    
    protected function getUploadRootDir()
    {
    	// On retourne le chemin relatif vers l'image pour notre code PHP
    	return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
  
    
    /**
     * Add device
     *
     * @param \App\Entity\Upv6\Devices $device
     * @return Families
     */
    public function addDevice(\App\Entity\Upv6\Devices $device)
    {
        $this->devices[] = $device;
    
        return $this;
    }

    /**
     * Remove device
     *
     * @param \App\Entity\Upv6\Devices $device
     */
    public function removeDevice(\App\Entity\Upv6\Devices $device)
    {
        $this->devices->removeElement($device);
    }

    /**
     * Get devices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDevices()
    {
        return $this->devices;
    }

	/**
     * Add actions
     *
     * @param \App\Entity\Upv6\\Actions $actions
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
     * @param \iot6\InteractBundle\Entity\Actions $actions
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
