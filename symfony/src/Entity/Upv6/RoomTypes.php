<?php

namespace App\Entity\Upv6;




use Doctrine\ORM\Mapping as ORM;

/**
 * RoomTypes
 *
 * @ORM\Table(name="room_types")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\RoomTypesRepository")
 */
class RoomTypes
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
     * @ORM\Column(name="icon_name", type="string", length=45, nullable=true)
     */
    private $iconName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Upv6\Rooms", mappedBy="roomType")
     */
    private $rooms;
    
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
     * @return RoomTypes
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
     * Set iconName
     *
     * @param string $iconName
     * @return RoomTypes
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
     * Constructor
     */
    public function __construct()
    {
        $this->rooms = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add room
     *
     * @param \App\Entity\Upv6\Rooms $rooms
     * @return RoomTypes
     */
    public function addRoom(\App\Entity\Upv6\Rooms $room)
    {
        $this->rooms[] = $room;
    	$rooms->setRoomType($this);
        return $this;
    }

    /**
     * Remove room
     *
     * @param \App\Entity\Upv6\Rooms $rooms
     */
    public function removeRoom(\App\Entity\Upv6\Rooms $room)
    {
        $this->rooms->removeElement($room);
    }

    /**
     * Get rooms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRooms()
    {
        return $this->rooms;
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
    	$this->iconName = $name;
    }
    
    public function getUploadDir()
    {
    	// On retourne le chemin relatif vers l'image pour un navigateur
    	return 'images/roomTypes';
    }
    
    protected function getUploadRootDir()
    {
    	// On retourne le chemin relatif vers l'image pour notre code PHP
    	return __DIR__.'../../../upload/'.$this->getUploadDir();
    }
}