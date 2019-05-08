<?php

namespace App\Entity\Gui;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
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
    private $serviceId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sku;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxUsers;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxDevices;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxStorage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServiceId(): ?int
    {
        return $this->serviceId;
    }

    public function setServiceId(?int $serviceId): self
    {
        $this->serviceId = $serviceId;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getMaxUsers(): ?int
    {
        return $this->maxUsers;
    }

    public function setMaxUsers(?int $maxUsers): self
    {
        $this->maxUsers = $maxUsers;

        return $this;
    }

    public function getMaxDevices(): ?int
    {
        return $this->maxDevices;
    }

    public function setMaxDevices(?int $maxDevices): self
    {
        $this->maxDevices = $maxDevices;

        return $this;
    }

    public function getMaxStorage(): ?int
    {
        return $this->maxStorage;
    }

    public function setMaxStorage(?int $maxStorage): self
    {
        $this->maxStorage = $maxStorage;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
}
