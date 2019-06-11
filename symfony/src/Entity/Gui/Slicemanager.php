<?php

namespace App\Entity\Gui;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Gui\SlicemanagerRepository")
 */
class Slicemanager
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Gui\Virtuallink", inversedBy="slicemanagers")
     */
    private $virtuallink;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Gui\Pop", inversedBy="slicemanagers")
     */
    private $popinstance;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Gui\Flavourkeys", inversedBy="slicemanagers")
     */
    private $flavourkeys;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slicename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slicedescription;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slcieprovider;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Gui\IotConfiguration", mappedBy="slicemanager")
     */
    private $iotConfigurations;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    public function __construct()
    {
        $this->virtuallink = new ArrayCollection();
        $this->popinstance = new ArrayCollection();
        $this->flavourkeys = new ArrayCollection();
        $this->iotConfigurations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Virtuallink[]
     */
    public function getVirtuallink(): Collection
    {
        return $this->virtuallink;
    }

    public function addVirtuallink(Virtuallink $virtuallink): self
    {
        if (!$this->virtuallink->contains($virtuallink)) {
            $this->virtuallink[] = $virtuallink;
        }

        return $this;
    }

    public function removeVirtuallink(Virtuallink $virtuallink): self
    {
        if ($this->virtuallink->contains($virtuallink)) {
            $this->virtuallink->removeElement($virtuallink);
        }

        return $this;
    }

    /**
     * @return Collection|Pop[]
     */
    public function getPopinstance(): Collection
    {
        return $this->popinstance;
    }

    public function addPopinstance(Pop $popinstance): self
    {
        if (!$this->popinstance->contains($popinstance)) {
            $this->popinstance[] = $popinstance;
        }

        return $this;
    }

    public function removePopinstance(Pop $popinstance): self
    {
        if ($this->popinstance->contains($popinstance)) {
            $this->popinstance->removeElement($popinstance);
        }

        return $this;
    }

    /**
     * @return Collection|Flavourkeys[]
     */
    public function getFlavourkeys(): Collection
    {
        return $this->flavourkeys;
    }

    public function addFlavourkey(Flavourkeys $flavourkey): self
    {
        if (!$this->flavourkeys->contains($flavourkey)) {
            $this->flavourkeys[] = $flavourkey;
        }

        return $this;
    }

    public function removeFlavourkey(Flavourkeys $flavourkey): self
    {
        if ($this->flavourkeys->contains($flavourkey)) {
            $this->flavourkeys->removeElement($flavourkey);
        }

        return $this;
    }

    public function getSlicename(): ?string
    {
        return $this->slicename;
    }

    public function setSlicename(string $slicename): self
    {
        $this->slicename = $slicename;

        return $this;
    }

    public function getSlicedescription(): ?string
    {
        return $this->slicedescription;
    }

    public function setSlicedescription(string $slicedescription): self
    {
        $this->slicedescription = $slicedescription;

        return $this;
    }

    public function getSlcieprovider(): ?string
    {
        return $this->slcieprovider;
    }

    public function setSlcieprovider(string $slcieprovider): self
    {
        $this->slcieprovider = $slcieprovider;

        return $this;
    }

    /**
     * @return Collection|IotConfiguration[]
     */
    public function getIotConfigurations(): Collection
    {
        return $this->iotConfigurations;
    }

    public function addIotConfiguration(IotConfiguration $iotConfiguration): self
    {
        if (!$this->iotConfigurations->contains($iotConfiguration)) {
            $this->iotConfigurations[] = $iotConfiguration;
            $iotConfiguration->setSlicemanager($this);
        }

        return $this;
    }

    public function removeIotConfiguration(IotConfiguration $iotConfiguration): self
    {
        if ($this->iotConfigurations->contains($iotConfiguration)) {
            $this->iotConfigurations->removeElement($iotConfiguration);
            // set the owning side to null (unless already changed)
            if ($iotConfiguration->getSlicemanager() === $this) {
                $iotConfiguration->setSlicemanager(null);
            }
        }

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
