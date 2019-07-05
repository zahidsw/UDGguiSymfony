<?php

namespace App\Entity\Gui;

use App\Entity\Gui\City;
use App\Entity\Gui\Device;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityDeviceRepository")
 */
class CityDevice
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $accreditedByCityId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $accreditedAccessProfile;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Gui\City", inversedBy="cityDevices",fetch="EAGER")
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Gui\Device", inversedBy="cityDevices",fetch="EAGER")
     */
    private $device;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwnership(): ?string
    {
        return $this->ownership;
    }

    public function setOwnership(?string $ownership): self
    {
        $this->ownership = $ownership;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(?Device $device): self
    {
        $this->device = $device;

        return $this;
    }

    public function getAccreditedAccessProfile(): ?int
    {
        return $this->accreditedAccessProfile;
    }

    public function setAccreditedAccessProfile(?int $accreditedAccessProfile): self
    {
        $this->accreditedAccessProfile = $accreditedAccessProfile;

        return $this;
    }
    
    public function getAccreditedByCityId(): ?int
    {
        return $this->accreditedByCityId;
    }

    public function setAccreditedByCityId(?int $accreditedByCityId): self
    {
        $this->accreditedByCityId = $accreditedByCityId;

        return $this;
    }
}
