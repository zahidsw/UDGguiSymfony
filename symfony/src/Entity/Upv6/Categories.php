<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categories
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CategoriesRepository")
 */
class Categories
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
     * @ORM\Column(name="internal_name", type="string", length=255, nullable=false)
     */
    private $internalName;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_name", type="string", length=255, nullable=true)
     */
    private $iconName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Upv6\Devices", mappedBy="category")
     */
    private $devices;
    
    private $file;


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
     * @return Categories
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
     * @return Categories
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
    	return 'images/categories';
    }
    
    protected function getUploadRootDir()
    {
    	// On retourne le chemin relatif vers l'image pour notre code PHP
    	return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->devices = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add devices
     *
     * @param \iot6\InteractBundle\Entity\Devices $device
     * @return Categories
     */
    public function addDevice(\App\Entity\Upv6\Devices $device)
    {
        $this->devices[] = $device;
    
        return $this;
    }

    /**
     * Remove device
     *
     * @param \iot6\InteractBundle\Entity\Devices $device
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
}