<?php

namespace App\Entity\Gui;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Gui\PopRepository")
 */
class Pop
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
    private $auth;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tenant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $keypair;

	/**
	 * @var Securitygroup[]|ArrayCollection
	 *
	 * @ORM\ManyToMany(targetEntity="App\Entity\Gui\securitygroup", cascade={"persist"})
	 * @ORM\JoinTable(name="pop_securitygroup")
	 * @ORM\OrderBy({"name": "ASC"})
	 */

    private $securitygroups;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $locationname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $longitude;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Gui\Slicemanager", mappedBy="popinstance")
     */
    private $slicemanagers;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    public function __construct()
    {
        $this->securitygroups = new ArrayCollection();
        $this->slicemanagers = new ArrayCollection();
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

    public function getAuth(): ?string
    {
        return $this->auth;
    }

    public function setAuth(string $auth): self
    {
        $this->auth = $auth;

        return $this;
    }

    public function getTenant(): ?string
    {
        return $this->tenant;
    }

    public function setTenant(string $tenant): self
    {
        $this->tenant = $tenant;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getKeypair(): ?string
    {
        return $this->keypair;
    }

    public function setKeypair(string $keypair): self
    {
        $this->keypair = $keypair;

        return $this;
    }

    /**
     * @return Collection|securitygroup[]
     */
    public function getSecuritygroup(): Collection
    {
        return $this->securitygroups;
    }

    public function addSecuritygroup(?Securitygroup ...$securitygroups): void
    {
	    foreach ($securitygroups as $securitygroup) {
		    if ( ! $this->securitygroups->contains( $securitygroup ) ) {
			    $this->$securitygroups->add( $securitygroup );
		    }
	    }
    }

    public function removeSecuritygroup(Securitygroup $securitygroup): void
    {

            $this->securitygroups->removeElement($securitygroup);
    }

	public function getPassword(): ?string
                            {
                                return $this->password;
                            }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getLocationname(): ?string
    {
        return $this->locationname;
    }

    public function setLocationname(?string $locationname): self
    {
        $this->locationname = $locationname;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection|Slicemanager[]
     */
    public function getSlicemanagers(): Collection
    {
        return $this->slicemanagers;
    }

    public function addSlicemanager(Slicemanager $slicemanager): self
    {
        if (!$this->slicemanagers->contains($slicemanager)) {
            $this->slicemanagers[] = $slicemanager;
            $slicemanager->addPopinstance($this);
        }

        return $this;
    }

    public function removeSlicemanager(Slicemanager $slicemanager): self
    {
        if ($this->slicemanagers->contains($slicemanager)) {
            $this->slicemanagers->removeElement($slicemanager);
            $slicemanager->removePopinstance($this);
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
