<?php

namespace App\Entity\Gui;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Gui\IotConfigurationRepository")
 */
class IotConfiguration
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $targetTempSensName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $targetTempSensURL;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tempThreshold;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $emergencySliceName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cameraIP;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cameraPort;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cameraUser;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cameraPassword;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $minimumBandwidth;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $maxBandwidth;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Gui\Slicemanager", inversedBy="iotConfigurations")
     */
    private $slicemanager;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    public function __construct()
    {
        $this->sliceconfiguration = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTargetTempSensName(): ?string
    {
        return $this->targetTempSensName;
    }

    public function setTargetTempSensName(string $targetTempSensName): self
    {
        $this->targetTempSensName = $targetTempSensName;

        return $this;
    }

    public function getTargetTempSensURL(): ?string
    {
        return $this->targetTempSensURL;
    }

    public function setTargetTempSensURL(string $targetTempSensURL): self
    {
        $this->targetTempSensURL = $targetTempSensURL;

        return $this;
    }

    public function getTempThreshold(): ?string
    {
        return $this->tempThreshold;
    }

    public function setTempThreshold(string $tempThreshold): self
    {
        $this->tempThreshold = $tempThreshold;

        return $this;
    }

    public function getEmergencySliceName(): ?string
    {
        return $this->emergencySliceName;
    }

    public function setEmergencySliceName(string $emergencySliceName): self
    {
        $this->emergencySliceName = $emergencySliceName;

        return $this;
    }

    public function getCameraIP(): ?string
    {
        return $this->cameraIP;
    }

    public function setCameraIP(string $cameraIP): self
    {
        $this->cameraIP = $cameraIP;

        return $this;
    }

    public function getCameraPort(): ?string
    {
        return $this->cameraPort;
    }

    public function setCameraPort(string $cameraPort): self
    {
        $this->cameraPort = $cameraPort;

        return $this;
    }

    public function getCameraUser(): ?string
    {
        return $this->cameraUser;
    }

    public function setCameraUser(string $cameraUser): self
    {
        $this->cameraUser = $cameraUser;

        return $this;
    }

    public function getCameraPassword(): ?string
    {
        return $this->cameraPassword;
    }

    public function setCameraPassword(string $cameraPassword): self
    {
        $this->cameraPassword = $cameraPassword;

        return $this;
    }

    public function getMinimumBandwidth(): ?string
    {
        return $this->minimumBandwidth;
    }

    public function setMinimumBandwidth(string $minimumBandwidth): self
    {
        $this->minimumBandwidth = $minimumBandwidth;

        return $this;
    }

    public function getMaxBandwidth(): ?string
    {
        return $this->maxBandwidth;
    }

    public function setMaxBandwidth(string $maxBandwidth): self
    {
        $this->maxBandwidth = $maxBandwidth;

        return $this;
    }

    /**
     * @return Collection|Slicemanager[]
     */
    public function getSliceconfiguration(): Collection
    {
        return $this->sliceconfiguration;
    }

    public function getSlicemanager(): ?Slicemanager
    {
        return $this->slicemanager;
    }

    public function setSlicemanager(?Slicemanager $slicemanager): self
    {
        $this->slicemanager = $slicemanager;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

}
