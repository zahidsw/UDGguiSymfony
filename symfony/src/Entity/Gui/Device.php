<?php

namespace App\Entity\Gui;

use App\Entity\Gui\City;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviceRepository")
 */
class Device
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $upv6DevicesId;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Gui\City", inversedBy="devices")
     */
    private $city;

    public function __construct()
    {
        $this->city = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUpv6DevicesId(): ?int
    {
        return $this->upv6DevicesId;
    }

    public function setUpv6DevicesId(int $upv6DevicesId): self
    {
        $this->upv6DevicesId = $upv6DevicesId;

        return $this;
    }

    /**
     * @return Collection|City[]
     */
    public function getCity(): Collection
    {
        return $this->city;
    }

    public function addCity(City $city): self
    {
        if (!$this->city->contains($city)) {
            $this->city[] = $city;
        }

        return $this;
    }

    public function removeCity(City $city): self
    {
        if ($this->city->contains($city)) {
            $this->city->removeElement($city);
        }

        return $this;
    }
}
