<?php

namespace App\Entity\Upv6;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserHasDevice
 *
 * @ORM\Table(name="user_has_device")
 * @ORM\Entity(repositoryClass="App\Repository\UserHasDeviceRepository")
 * @ORM\Entity
 */
class UserHasDevice
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
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="device_id", type="integer", nullable=false)
     */
    private $deviceId;

    /**
     * @var integer
     *
     * @ORM\Column(name="access_profile", type="integer", nullable=true)
     */
    private $accessProfile;



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
     * Set userId
     *
     * @param integer $userId
     * @return UserHasDevice
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    
        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set deviceId
     *
     * @param integer $deviceId
     * @return UserHasDevice
     */
    public function setDeviceId($deviceId)
    {
        $this->deviceId = $deviceId;
    
        return $this;
    }

    /**
     * Get deviceId
     *
     * @return integer 
     */
    public function getDeviceId()
    {
        return $this->deviceId;
    }

    /**
     * Set accessProfile
     *
     * @param boolean $accessProfile
     * @return UserHasDevice
     */
    public function setAccessProfile($accessProfile)
    {
        $this->accessProfile = $accessProfile;
    
        return $this;
    }

    /**
     * Get accessProfile
     *
     * @return boolean 
     */
    public function getAccessProfile()
    {
        return $this->accessProfile;
    }
}