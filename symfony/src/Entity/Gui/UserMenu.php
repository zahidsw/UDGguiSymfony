<?php

namespace App\Entity\Gui;


use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table(name="user_menu")
 * @ORM\Entity()
 */
class UserMenu
{
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="App\Entity\Gui\User")
	 */
	private $user;
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="App\Entity\Gui\Menu")
	 */
	private $menu;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="color", type="string", length=255)
	 */
	private $color;

    /**
     * Set color
     *
     * @param string $color
     * @return UserMenu
     */
    public function setColor($color)
    {
        $this->color = $color;
    
        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set user
     *
     * @param \App\Entity\Gui\User $user
     * @return UserMenu
     */
    public function setUser(\App\Entity\Gui\User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \App\Entity\Gui\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set menu
     *
     * @param \App\Entity\Gui\Menu $menu
     * @return UserMenu
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
}