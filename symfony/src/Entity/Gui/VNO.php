<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VNORepository")
 */

class VNO
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
	 * @ORM\Column(type="string", length=255)
	 */
	private $password;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $locationname;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $latitude;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $longitude;



	public function __construct()
	{

		$this->tags = new ArrayCollection();
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


	public function getAuthor(): User
	{
		return $this->author;
	}

	public function setAuthor(?User $author): void
	{
		$this->author = $author;
	}

	public function addTag(?Tag ...$tags): void
	{
		foreach ($tags as $tag) {
			if (!$this->tags->contains($tag)) {
				$this->tags->add($tag);
			}
		}
	}

	public function removeTag(Tag $tag): void
	{
		$this->tags->removeElement($tag);
	}

	public function getTags(): Collection
	{
		return $this->tags;
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

	public function setLocationname(string $locationname): self
	{
		$this->locationname = $locationname;

		return $this;
	}

	public function getLatitude(): ?string
	{
		return $this->latitude;
	}

	public function setLatitude(string $latitude): self
	{
		$this->latitude = $latitude;

		return $this;
	}

	public function getLongitude(): ?string
	{
		return $this->longitude;
	}

	public function setLongitude(string $longitude): self
	{
		$this->longitude = $longitude;

		return $this;
	}

}
