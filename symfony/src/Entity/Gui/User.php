<?php

namespace App\Entity\Gui;

use App\Entity\Gui\Purchase;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

     /**
     * @ORM\Column(type="string")
     */
    private $userName;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
	 * @ORM\ManyToMany(targetEntity="App\Entity\Gui\Route", cascade={"persist"})
	 */
    private $routes;

    /**
     * @var \DateTime|null
     */
    protected $lastLogin;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

     /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $subjectToken;

    /**
     * @ORM\Column(type="string")
     */
    private $keyrockId;


     /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Gui\City", inversedBy="Users")
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Gui\Purchase", mappedBy="user")
     */
    private $purchases;


 
    public function __construct()
    {
        $this->purchases = new ArrayCollection();
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



    public function getKeyrockId(): ?String
    {
        return $this->keyrockId;
    }

    public function setKeyrockId(String $keyrockId): self
    {
        $this->keyrockId = $keyrockId;

        return $this;
    }

    public function getSubjectToken(): ?String
    {
        return $this->subjectToken;
    }

    public function setSubjectToken(String $subjectToken): self
    {
        $this->subjectToken = $subjectToken;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }
    

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function addRole(String $roles): self
    {
        array_push($this->roles,$roles);
        // $this->roles = $roles;

        return $this;
    }

    public function resetRole(): self
    {
        $this->roles = [];
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Add route
     *
     * @param \App\Entity\Gui\Route $route
     * @return User
     */
    public function addRoute(\App\Entity\Gui\Route $route)
    {
        $this->routes[] = $route;
    
        return $this;
    }

    /**
     * Remove route
     *
     * @param \App\Entity\Gui\Route $route
     */
    public function removeRoute(\App\Entity\Gui\Route $route)
    {
        $this->routes->removeElement($route);
    }

    /**
     * Get routes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoutes()
    {
        return $this->routes;
    }

     /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->password,
            $this->salt,
            $this->usernameCanonical,
            $this->username,
            $this->enabled,
            $this->id,
            $this->email,
            $this->emailCanonical,
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        if (13 === count($data)) {
            // Unserializing a User object from 1.3.x
            unset($data[4], $data[5], $data[6], $data[9], $data[10]);
            $data = array_values($data);
        } elseif (11 === count($data)) {
            // Unserializing a User from a dev version somewhere between 2.0-alpha3 and 2.0-beta1
            unset($data[4], $data[7], $data[8]);
            $data = array_values($data);
        }
        list(
            $this->password,
            $this->salt,
            $this->usernameCanonical,
            $this->username,
            $this->enabled,
            $this->id,
            $this->email,
            $this->emailCanonical
        ) = $data;
    }

    /**
     * Gets the last login time.
     *
     * @return \DateTime|null
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled($boolean)
    {
        $this->enabled = (bool) $boolean;
        return $this;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    
   public function __toString()
   {
      return strval( $this->getId() );
   }

   /**
    * @return Collection|Purchase[]
    */
   public function getPurchases(): Collection
   {
       return $this->purchases;
   }

   public function addPurchase(Purchase $purchase): self
   {
       if (!$this->purchases->contains($purchase)) {
           $this->purchases[] = $purchase;
           $purchase->setUser($this);
       }

       return $this;
   }

   public function removePurchase(Purchase $purchase): self
   {
       if ($this->purchases->contains($purchase)) {
           $this->purchases->removeElement($purchase);
           // set the owning side to null (unless already changed)
           if ($purchase->getUser() === $this) {
               $purchase->setUser(null);
           }
       }

       return $this;
   }
  


}
