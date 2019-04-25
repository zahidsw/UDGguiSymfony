<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 * 
 */
class UsersMiddleware
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
     * @ORM\Column(name="user_id", type="text", nullable=true)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="text", nullable=true)
     */
    private $password;

    /**
     * @var interger
     *
     * @ORM\Column(name="access_profile", type="integer", nullable=true)
     */
    private $accessProfile;
    
    /**
     * @var string
     *
     * @ORM\Column(name="session_id", type="text", nullable=true)
     */
    private $sessionId;    

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
     * @param string $userId
     * @return UsersMiddleware
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    
        return $this;
    }

    /**
     * Get userId
     *
     * @return string 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return UsersMiddleware
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
     * Set password
     *
     * @param string $password
     * @return UsersMiddleware
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set accessProfile
     *
     * @param integer $accessProfile
     * @return UsersMiddleware
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
     * Set session ID
     *
     * @param string $sessionId
     * @return UsersMiddleware
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    
        return $this;
    }

    /**
     * Get session ID
     *
     * @return string 
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }
}
