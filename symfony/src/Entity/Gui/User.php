<?php

namespace App\Entity\Gui;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity
 * 
 *
 * @ORM\AttributeOverrides({
 *      @ORM\AttributeOverride(name="salt",
 *          column=@ORM\Column(
 *              name     = "salt",
 *              nullable = true
 *            
 *          )
 *      ),
 *       @ORM\AttributeOverride(name="password",
 *          column=@ORM\Column(
 *              name     = "password",
 *              nullable = true
 *            
 *          )
 *      )
 * 
 * })
 */
class User extends BaseUser
{
    	
	/**
	 * Constructor
	 */
    public function __construct()
    {
    	parent::__construct();
    	$this->locked = false;
        $this->routes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->expired = false;
        $this->credentialsExpired = false;
    }
    
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToMany(targetEntity="App\Entity\Gui\Route", cascade={"persist"})
	 */
    private $routes;
    
    /**
	 *
	 *
	 * @ORM\Column(name="locked", type="boolean")
	 */
    protected $locked;

    /**
	 *
	 * @ORM\Column(name="expired", type="boolean")
	 */
    protected $expired;

    /**
	 *
	 * @ORM\Column(name="credentials_expired", type="boolean")
	 */
    protected $credentialsExpired;

    /**
	 *
	 * @ORM\Column(name="credentials_expire_at", type="datetime")
	 */
    protected $credentialsExpireAt;

    /** @ORM\Column(name="fiware_id", type="string", length=255, nullable=true) */
    protected $fiware_id;

    /** @ORM\Column(name="fiware_access_token", type="string", length=255, nullable=true) */
    protected $fiware_access_token;


    public function setFiwareId($fiwareId) {
        $this->fiware_id = $fiwareId;

        return $this;
    }

    public function getFiwareId() {
        return $this->fiware_id;
    }

    public function setFiwareAccessToken($fiwareAccessToken) {
        $this->fiware_access_token = $fiwareAccessToken;

        return $this;
    }

    public function getFiwareAccessToken() {
        return $this->fiware_access_token;
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
     * Add route
     *
     * @param \App\Entity\Gui\Route $route
     * @return User
     */
    public function addRoute(\App\Entity\Gui\Route $route)
    {
        $this->routes[] = $route;
    
        return $this;
    }

    /**
     * Remove route
     *
     * @param \App\Entity\Gui\Route $route
     */
    public function removeRoute(\App\Entity\Gui\Route $route)
    {
        $this->routes->removeElement($route);
    }

    /**
     * Get routes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoutes()
    {
        return $this->routes;
    }
    
    public static function cmp_username(User $a, User $b)
    {
    	if ($a->getUsername() == $b->getUsername()) {
    		return 0;
    	}
    	return ($a->getUsername() < $b->getUsername()) ? -1 : 1;
    }

    public function setLocked($boolean)
    {
        $this->locked = $boolean;

        return $this;
    }

    public function isAccountNonLocked()
    {
        return !$this->locked;
    }

    public function isLocked()
    {
        return !$this->isAccountNonLocked();
    }

    /**
     * Sets this user to expired.
     *
     * @param Boolean $boolean
     *
     * @return User
     */
    public function setExpired($boolean)
    {
        $this->expired = (Boolean) $boolean;

        return $this;
    }

    public function isExpired()
    {
        return !$this->isAccountNonExpired();
    }

    public function isCredentialsNonExpired()
    {
        if (true === $this->credentialsExpired) {
            return false;
        }

        if (null !== $this->credentialsExpireAt && $this->credentialsExpireAt->getTimestamp() < time()) {
            return false;
        }

        return true;
    }

    public function isCredentialsExpired()
    {
        return !$this->isCredentialsNonExpired();
    }

    public function setCredentialsExpired($boolean)
    {
        $this->credentialsExpired = $boolean;

        return $this;
    }




}