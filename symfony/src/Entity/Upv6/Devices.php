<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * Devices
 *
 * @ORM\Table(name="devices")
 * @ORM\Entity(repositoryClass="App\Repository\DevicesRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Devices
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
     * @ORM\Column(name="ipv6address", type="string", length=39, nullable=true)
     */
    private $ipv6address;

    /**
     * @var string
     *
     * @ORM\Column(name="physical_code", type="string", length=255, nullable=false)
     */
    private $physicalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="assigned_name", type="string", length=100, nullable=true)
     */
    private $assignedName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="text", nullable=true)
     */
    private $comments;

    /**
     * @var integer
     *
     * @ORM\Column(name="validation_status", type="integer", nullable=false)
     */
    private $validationStatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="detected_at", type="datetime", nullable=false)
     */
    private $detectedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_data_at", type="datetime", nullable=false)
     */
    private $lastDataAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_change", type="datetime", nullable=false)
     */
    private $lastChange;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Families", inversedBy="devices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $family;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Categories", inversedBy="devices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Protocols")
     * @ORM\JoinColumn(nullable=false)
     */
    private $protocol;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Modules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $module;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $card;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Models")
     * @ORM\JoinColumn(nullable=false)
     */
    private $model;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Rooms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $room;

    /**
     * @var float
     *
     * @ORM\Column(name="position_x", type="float", nullable=true)
     */
    private $positionX;

    /**
     * @var float
     *
     * @ORM\Column(name="position_y", type="float", nullable=true)
     */
    private $positionY;

    /**
     * @var float
     *
     * @ORM\Column(name="position_z", type="float", nullable=true)
     */
    private $positionZ;
    
    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", nullable=true)
     */
    private $latitude;
    
    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", nullable=true)
     */
    private $longitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="importance_level", type="integer", nullable=false)
     */
    private $importanceLevel;

    /**
     * @var boolean
     *
     * @ORM\Column(name="esp_override", type="boolean", nullable=true)
     */
    private $espOverride;

    /**
     * @var integer
     *
     * @ORM\Column(name="energy_level", type="integer", nullable=false)
     */
    private $energylevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="last_energy_level", type="integer", nullable=false)
     */
    private $lastEnergyLevel;

    /**
     * @var boolean
     *
     * @ORM\Column(name="proxy_enabled", type="boolean", nullable=true)
     */
    private $proxyEnabled;

    /**
     * @var integer
     *
     * @ORM\Column(name="access_profile", type="integer", nullable=false)
     */
    private $accessProfile;

    /**
     * @var string
     *
     * @ORM\Column(name="host_id", type="text", nullable=true)
     */
    private $hostId;

    /**
     * @var string
     *
     * @ORM\Column(name="mac_address", type="text", nullable=true)
     */
    private $macAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="epc_address", type="text", nullable=true)
     */
    private $epcAddress;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Upv6\Variables", mappedBy="device")
     **/
    private $variables;

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_app", type="boolean", nullable=true)
     */
    private $privacyApp;
    
    
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
     * Set ipv6address
     *
     * @param string $ipv6address
     * @return Devices
     */
    public function setIpv6address($ipv6address)
    {
        $this->ipv6address = $ipv6address;
    
        return $this;
    }

    /**
     * Get ipv6address
     *
     * @return string 
     */
    public function getIpv6address()
    {
        return $this->ipv6address;
    }

    /**
     * Set physicalCode
     *
     * @param string $physicalCode
     * @return Devices
     */
    public function setPhysicalCode($physicalCode)
    {
        $this->physicalCode = $physicalCode;
    
        return $this;
    }

    /**
     * Get physicalCode
     *
     * @return string 
     */
    public function getPhysicalCode()
    {
        return $this->physicalCode;
    }

    /**
     * Set assignedName
     *
     * @param string $assignedName
     * @return Devices
     */
    public function setAssignedName($assignedName)
    {
        $this->assignedName = $assignedName;
    
        return $this;
    }

    /**
     * Get assignedName
     *
     * @return string 
     */
    public function getAssignedName()
    {
        return $this->assignedName;
    }
    
    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
    	return $this->assignedName;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Devices
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
     * Set comments
     *
     * @param string $comments
     * @return Devices
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    
        return $this;
    }

    /**
     * Get comments
     *
     * @return string 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set validationStatus
     *
     * @param integer $validationStatus
     * @return Devices
     */
    public function setValidationStatus($validationStatus)
    {
        $this->validationStatus = $validationStatus;
    
        return $this;
    }

    /**
     * Get validationStatus
     *
     * @return integer 
     */
    public function getValidationStatus()
    {
        return $this->validationStatus;
    }

    /**
     * Set detectedAt
     *
     * @param \DateTime $detectedAt
     * @return Devices
     */
    public function setDetectedAt($detectedAt)
    {
        $this->detectedAt = $detectedAt;
    
        return $this;
    }

    /**
     * Get detectedAt
     *
     * @return \DateTime 
     */
    public function getDetectedAt()
    {
        return $this->detectedAt;
    }

    /**
     * Set lastDataAt
     *
     * @param \DateTime $lastDataAt
     * @return Devices
     */
    public function setLastDataAt($lastDataAt)
    {
        $this->lastDataAt = $lastDataAt;
    
        return $this;
    }

    /**
     * Get lastDataAt
     *
     * @return \DateTime 
     */
    public function getLastDataAt()
    {
        return $this->lastDataAt;
    }

    /**
     * Set protocolId
     *
     * @param string $protocolId
     * @return Devices
     */
    public function setProtocolId($protocolId)
    {
        $this->protocolId = $protocolId;
    
        return $this;
    }

    /**
     * Get protocolId
     *
     * @return string 
     */
    public function getProtocolId()
    {
        return $this->protocolId;
    }

    /**
     * Set cardId
     *
     * @param integer $cardId
     * @return Devices
     */
    public function setCardId($cardId)
    {
        $this->cardId = $cardId;
    
        return $this;
    }

    /**
     * Get cardId
     *
     * @return integer 
     */
    public function getCardId()
    {
        return $this->cardId;
    }

    /**
     * Set modelId
     *
     * @param integer $modelId
     * @return Devices
     */
    public function setModelId($modelId)
    {
        $this->modelId = $modelId;
    
        return $this;
    }

    /**
     * Get modelId
     *
     * @return integer 
     */
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * Set positionX
     *
     * @param float $positionX
     * @return Devices
     */
    public function setPositionX($positionX)
    {
        $this->positionX = $positionX;
    
        return $this;
    }

    /**
     * Get positionX
     *
     * @return float 
     */
    public function getPositionX()
    {
        return $this->positionX;
    }

    /**
     * Set positionY
     *
     * @param float $positionY
     * @return Devices
     */
    public function setPositionY($positionY)
    {
        $this->positionY = $positionY;
    
        return $this;
    }

    /**
     * Get positionY
     *
     * @return float 
     */
    public function getPositionY()
    {
        return $this->positionY;
    }

    /**
     * Set positionZ
     *
     * @param float $positionZ
     * @return Devices
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
     * Set latitude
     *
     * @param float $latitude
     * @return Devices
     */
    public function setLatitude($latitude)
    {
    	$this->latitude = $latitude;
    
    	return $this;
    }
    
    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
    	return $this->latitude;
    }
    
    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Devices
     */
    public function setLongitude($longitude)
    {
    	$this->longitude = $longitude;
    
    	return $this;
    }
    
    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
    	return $this->longitude;
    }

    /**
     * Set importanceLevel
     *
     * @param integer $importanceLevel
     * @return Devices
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
     * Set espOverride
     *
     * @param boolean $espOverride
     * @return Devices
     */
    public function setEspOverride($espOverride)
    {
        $this->espOverride = $espOverride;
    
        return $this;
    }

    /**
     * Get espOverride
     *
     * @return boolean 
     */
    public function getEspOverride()
    {
        return $this->espOverride;
    }

    /**
     * Set energylevel
     *
     * @param integer $energylevel
     * @return Devices
     */
    public function setEnergylevel($energylevel)
    {
        $this->energylevel = $energylevel;
    
        return $this;
    }

    /**
     * Get energylevel
     *
     * @return integer 
     */
    public function getEnergylevel()
    {
        return $this->energylevel;
    }

    /**
     * Set lastEnergyLevel
     *
     * @param integer $lastEnergyLevel
     * @return Devices
     */
    public function setLastEnergyLevel($lastEnergyLevel)
    {
        $this->lastEnergyLevel = $lastEnergyLevel;
    
        return $this;
    }

    /**
     * Get lastEnergyLevel
     *
     * @return integer 
     */
    public function getLastEnergyLevel()
    {
        return $this->lastEnergyLevel;
    }

    /**
     * Set proxyEnabled
     *
     * @param boolean $proxyEnabled
     * @return Devices
     */
    public function setProxyEnabled($proxyEnabled)
    {
        $this->proxyEnabled = $proxyEnabled;
    
        return $this;
    }

    /**
     * Get proxyEnabled
     *
     * @return boolean 
     */
    public function getProxyEnabled()
    {
        return $this->proxyEnabled;
    }

    /**
     * Set accessProfile
     *
     * @param integer $accessProfile
     * @return Devices
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
     * Set hostId
     *
     * @param string $hostId
     * @return Devices
     */
    public function setHostId($hostId)
    {
        $this->hostId = $hostId;
    
        return $this;
    }

    /**
     * Get hostId
     *
     * @return string 
     */
    public function getHostId()
    {
        return $this->hostId;
    }

    /**
     * Set macAddress
     *
     * @param string $macAddress
     * @return Devices
     */
    public function setMacAddress($macAddress)
    {
        $this->macAddress = $macAddress;
    
        return $this;
    }

    /**
     * Get macAddress
     *
     * @return string 
     */
    public function getMacAddress()
    {
        return $this->macAddress;
    }

    /**
     * Set epcAddress
     *
     * @param string $epcAddress
     * @return Devices
     */
    public function setEpcAddress($epcAddress)
    {
        $this->epcAddress = $epcAddress;
    
        return $this;
    }

    /**
     * Get epcAddress
     *
     * @return string 
     */
    public function getEpcAddress()
    {
        return $this->epcAddress;
    }

    /**
     * Set family
     *
     * @param \iot6\InteractBundle\Entity\Families $family
     * @return Devices
     */
    public function setFamily(\App\Entity\Upv6\Families $family)
    {
        $this->family = $family;
    
        return $this;
    }

    /**
     * Get family
     *
     * @return \iot6\InteractBundle\Entity\Families 
     */
    public function getFamily()
    {
        return $this->family;
    }
    
    /**
     * Set category
     *
     * @param \iot6\InteractBundle\Entity\Categories $category
     * @return Devices
     */
    public function setCategory(\App\Entity\Upv6\Categories $category)
    {
    	$this->category = $category;
    
    	return $this;
    }
    
    /**
     * Get category
     *
     * @return \iot6\InteractBundle\Entity\Categories
     */
    public function getCategory()
    {
    	return $this->category;
    }

    /**
     * Set module
     *
     * @param \iot6\InteractBundle\Entity\Modules $module
     * @return Devices
     */
    public function setModule(\App\Entity\Upv6\Modules $module)
    {
        $this->module = $module;
    
        return $this;
    }

    /**
     * Get module
     *
     * @return \iot6\InteractBundle\Entity\Modules 
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set room
     *
     * @param \iot6\InteractBundle\Entity\Rooms $room
     * @return Devices
     */
    public function setRoom(\App\Entity\Upv6\Rooms $room)
    {
        $this->room = $room;
    
        return $this;
    }

    /**
     * Get room
     *
     * @return \iot6\InteractBundle\Entity\Rooms 
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Set protocol
     *
     * @param \iot6\InteractBundle\Entity\Protocols $protocol
     * @return Devices
     */
    public function setProtocol(\App\Entity\Upv6\Protocols $protocol)
    {
        $this->protocol = $protocol;
    
        return $this;
    }

    /**
     * Get protocol
     *
     * @return \iot6\InteractBundle\Entity\Protocols 
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Set card
     *
     * @param \iot6\InteractBundle\Entity\Cards $card
     * @return Devices
     */
    public function setCard(\App\Entity\Upv6\Cards $card)
    {
        $this->card = $card;
    
        return $this;
    }

    /**
     * Get card
     *
     * @return \App\Entity\Upv6\Cards 
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * Set model
     *
     * @param \App\Entity\Upv6\Models $model
     * @return Devices
     */
    public function setModel(\App\Entity\Upv6\Models $model)
    {
        $this->model = $model;
    
        return $this;
    }

    /**
     * Get model
     *
     * @return \App\Entity\Upv6\Models 
     */
    public function getModel()
    {
        return $this->model;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->variables = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add variable
     *
     * @param \iot6\InteractBundle\Entity\Variables $variable
     * @return Device
     */
    public function addVariable(\App\Entity\Upv6\Variables $variable)
    {
        $this->variables[] = $variable;
    
        return $this;
    }

    /**
     * Remove variable
     *
     * @param \iot6\InteractBundle\Entity\Variables $variable
     */
    public function removeVariable(\App\Entity\Upv6\Variables $variable)
    {
        $this->variables->removeElement($variable);
    }

    /**
     * Get variables
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Set lastChange
     *
     * @param \DateTime $lastChange
     * @return Devices
     */
    public function setLastChange($lastChange)
    {
        $this->lastChange = $lastChange;
    
        return $this;
    }

    /**
     * Get lastChange
     *
     * @return \DateTime 
     */
    public function getLastChange()
    {
        return $this->lastChange;
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function updateLastChangeDate()
    {
    	$this->setLastChange(new \Datetime());
    }

    /**
     * Set privacyApp
     *
     * @param boolean $privacyApp
     * @return Devices
     */
    public function setPrivacyApp($privacyApp)
    {
        $this->privacyApp = $privacyApp;
    
        return $this;
    }

    /**
     * Get privacyApp
     *
     * @return boolean 
     */
    public function getPrivacyApp()
    {
        return $this->privacyApp;
    }
}