<?php

namespace App\Entity\Gui;

use App\Entity\Gui\CityDevice;
use App\Entity\Gui\Device;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 * @ORM\Table(name="city")
 */
class City
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

   /**
     * @ORM\OneToMany(targetEntity="App\Entity\Gui\User", mappedBy="city",fetch="EAGER")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Gui\CityDevice", mappedBy="city",fetch="EAGER")
     */
    private $cityDevices;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->devices = new ArrayCollection();
        $this->cityDevices = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCity($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getCity() === $this) {
                $user->setCity(null);
            }
        }

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
            $cityDevice->setCity($this);
        }

        return $this;
    }

    public function removeCityDevice(CityDevice $cityDevice): self
    {
        if ($this->cityDevices->contains($cityDevice)) {
            $this->cityDevices->removeElement($cityDevice);
            // set the owning side to null (unless already changed)
            if ($cityDevice->getCity() === $this) {
                $cityDevice->setCity(null);
            }
        }

        return $this;
    }
}

