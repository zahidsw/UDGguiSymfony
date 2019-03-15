<?php

namespace App\Entity\Gui;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="menu")
 * @ORM\Entity
 */
class Menu
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
	 * @ORM\Column(name="name", type="string", length=255, unique=true)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="color", type="string", length=255)
	 */
	private $color;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Gui\Route",fetch="LAZY")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $route;

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 * @return Menu
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}

	/**
	 * Get name
	 *
	 * @return string 
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set color
	 *
	 * @param string $color
	 * @return Menu
	 */
	public function setColor($color) {
		$this->color = $color;

		return $this;
	}

	/**
	 * Get color
	 *
	 * @return string 
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * Set route
	 *
	 * @param App\Entity\Gui\Route $route
	 * @return Menu
	 */
	public function setRoute(\App\Entity\Gui\Route $route) {
		$this->route = $route;

		return $this;
	}

	/**
	 * Get route
	 *
	 * @return \App\Entity\Gui\Route 
	 */
	public function getRoute() {
		return $this->route;
	}
}
