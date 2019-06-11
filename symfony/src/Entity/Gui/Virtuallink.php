<?php

namespace App\Entity\Gui;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Gui\VirtuallinkRepository")
 */
class Virtuallink
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
    private $neworkname;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Gui\Slicemanager", mappedBy="virtuallink")
     */
    private $slicemanagers;

    public function __construct()
    {
        $this->slicemanagers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNeworkname(): ?string
    {
        return $this->neworkname;
    }

    public function setNeworkname(string $neworkname): self
    {
        $this->neworkname = $neworkname;

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
            $slicemanager->addVirtuallink($this);
        }

        return $this;
    }

    public function removeSlicemanager(Slicemanager $slicemanager): self
    {
        if ($this->slicemanagers->contains($slicemanager)) {
            $this->slicemanagers->removeElement($slicemanager);
            $slicemanager->removeVirtuallink($this);
        }

        return $this;
    }

}
