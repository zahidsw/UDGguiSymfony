<?php

namespace App\Entity\Upv6;


use Doctrine\ORM\Mapping as ORM;

/**
 * Floors
 *
 * @ORM\Table(name="floors")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\FloorsRepository")
 */
class Floors
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Buildings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $building;

    /**
     * @var string
     *
     * @ORM\Column(name="image_name", type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @var float
     *
     * @ORM\Column(name="position_z", type="float", nullable=true)
     */
    private $positionZ;
	
	 /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Locations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $location;
    
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
     * Set name
     *
     * @param string $name
     * @return Floors
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
     * Set building
     *
     * @param integer $building
     * @return Floors
     */
    public function setBuilding($building)
    {
        $this->building = $building;
    
        return $this;
    }

    /**
     * Get building
     *
     * @return Buildings 
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     * @return Floors
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
     * Set positionZ
     *
     * @param float $positionZ
     * @return Floors
     */
    public function setPositionZ($positionZ)
    {
        $this->positionZ = $positionZ;
    
        return $this;
    }

    /**
     * Get positionZ
     *
     * @return float 
     */
    public function getPositionZ()
    {
        return $this->positionZ;
    }
    
	/**
     * Set location
     *
     * @param integer $location
     * @return Floors
     */
    public function setLocation($location)
    {
        $this->location = $location;
    
        return $this;
    }

    /**
     * Get location
     *
     * @return Locations 
     */
    public function getLocation()
    {
        return $this->location;
    }
	
    /**
     * Set file
     *
     * @param string $file
     * @return Floors
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
    	$this->imageName = $name;
    }
    
    public function getUploadDir()
    {
    	// On retourne le chemin relatif vers l'image pour un navigateur
    	return 'images/floors';
    }
    
    protected function getUploadRootDir()
    {
    	// On retourne le chemin relatif vers l'image pour notre code PHP
    	return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
}
