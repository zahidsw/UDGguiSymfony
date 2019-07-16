<?php

namespace App\Entity\Gui;

use App\Entity\Gui\CityDevice;
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
     * @ORM\OneToMany(targetEntity="App\Entity\Gui\CityDevice", mappedBy="device")
     */
    private $cityDevices;

    public function __construct()
    {
        $this->city = new ArrayCollection();
        $this->cityDevices = new ArrayCollection();
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
     * @return Collection|CityDevice[]
     */
    public function getCityDevices(): Collection
    {
        return $this->cityDevices;
    }

    public function addCityDevice(CityDevice $cityDevice): self
    {
        if (!$this->cityDevices->contains($cityDevice)) {
            $this->cityDevices[] = $cityDevice;
            $cityDevice->setDevice($this);
        }

        return $this;
    }

    public function removeCityDevice(CityDevice $cityDevice): self
    {
        if ($this->cityDevices->contains($cityDevice)) {
            $this->cityDevices->removeElement($cityDevice);
            // set the owning side to null (unless already changed)
            if ($cityDevice->getDevice() === $this) {
                $cityDevice->setDevice(null);
            }
        }

        return $this;
    }
}
