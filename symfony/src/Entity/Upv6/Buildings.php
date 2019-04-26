<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * Buildings
 *
 * @ORM\Table(name="buildings")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\BuildingsRepository")
 */
class Buildings
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
     * @var string
     *
     * @ORM\Column(name="image_name", type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @var integer
     *
     * @ORM\Column(name="importance_level", type="integer", nullable=false)
     */
    private $importanceLevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="energy_level", type="integer", nullable=false)
     */
    private $energyLevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="esp_id", type="integer", nullable=false)
     */
    private $espId;

    /**
     * @var integer
     *
     * @ORM\Column(name="building_state", type="integer", nullable=false)
     */
    private $buildingState;

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
     * @return Buildings
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
     * Set imageName
     *
     * @param string $imageName
     * @return Buildings
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
     * Set importanceLevel
     *
     * @param integer $importanceLevel
     * @return Buildings
     */
    public function setImportanceLevel($importanceLevel)
    {
        $this->importanceLevel = $importanceLevel;
    
        return $this;
    }

    /**
     * Get importanceLevel
     *
     * @return integer 
     */
    public function getImportanceLevel()
    {
        return $this->importanceLevel;
    }

    /**
     * Set energyLevel
     *
     * @param integer $energyLevel
     * @return Buildings
     */
    public function setEnergyLevel($energyLevel)
    {
        $this->energyLevel = $energyLevel;
    
        return $this;
    }

    /**
     * Get energyLevel
     *
     * @return integer 
     */
    public function getEnergyLevel()
    {
        return $this->energyLevel;
    }

    /**
     * Set espId
     *
     * @param integer $espId
     * @return Buildings
     */
    public function setEspId($espId)
    {
        $this->espId = $espId;
    
        return $this;
    }

    /**
     * Get espId
     *
     * @return integer 
     */
    public function getEspId()
    {
        return $this->espId;
    }

    /**
     * Set buildingState
     *
     * @param integer $buildingState
     * @return Buildings
     */
    public function setBuildingState($buildingState)
    {
        $this->buildingState = $buildingState;
    
        return $this;
    }

    /**
     * Get buildingState
     *
     * @return integer 
     */
    public function getBuildingState()
    {
        return $this->buildingState;
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
    	return 'images/buildings';
    }
    
    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
    	return __DIR__.'../../../upload/'.$this->getUploadDir();
    }
}