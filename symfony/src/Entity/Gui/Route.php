<?php

namespace App\Entity\Gui;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="route")
 * @ORM\Entity
 */
class Route
{
     /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=255)
     */
    private $displayName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="bundle_prefix", type="string", length=255)
     */
    private $bundlePrefix;

    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=190, unique=true)
     */
    private $route;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="visibleForBookmark", type="boolean")
     */
    private $visibleForBookmark;
	
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Gui\Menu",fetch="LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    private $menu;

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
     * Set displayName
     *
     * @param string $displayName
     * @return Route
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    
        return $this;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set route
     *
     * @param string $route
     * @return Route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    
        return $this;
    }

    /**
     * Get route
     *
     * @return string 
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set visibleForBookmark
     *
     * @param boolean $visibleForBookmark
     * @return Route
     */
    public function setVisibleForBookmark($visibleForBookmark)
    {
        $this->visibleForBookmark = $visibleForBookmark;
    
        return $this;
    }

    /**
     * Get visibleForBookmark
     *
     * @return boolean 
     */
    public function getVisibleForBookmark()
    {
        return $this->visibleForBookmark;
    }

    /**
     * Set menu
     *
     * @param \App\Entity\Gui\Menu $menu
     * @return Route
     */
    public function setMenu(\App\Entity\Gui\Menu $menu)
    {
        $this->menu = $menu;
    
        return $this;
    }

    /**
     * Get menu
     *
     * @return \App\Entity\Gui\Menu 
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set bundlePrefix
     *
     * @param string $bundlePrefix
     * @return Route
     */
    public function setBundlePrefix($bundlePrefix)
    {
        $this->bundlePrefix = $bundlePrefix;
    
        return $this;
    }

    /**
     * Get bundlePrefix
     *
     * @return string 
     */
    public function getBundlePrefix()
    {
        return $this->bundlePrefix;
    }
}
