<?php

namespace App\Entity\Gui;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * Defines the properties of the Tag entity to represent the post tags.

 * @ORM\Entity(repositoryClass="App\Repository\Gui\SecuritygroupRepository")
 */
class Securitygroup implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

	/**
	 * {@inheritdoc}
	 */
	public function jsonSerialize(): string
	{
		// This entity implements JsonSerializable (http://php.net/manual/en/class.jsonserializable.php)
		// so this method is used to customize its JSON representation when json_encode()
		// is called, for example in tags|json_encode (app/Resources/views/form/fields.html.twig)

		return $this->name;
	}

	public function __toString(): string
	{
		return $this->name;
	}
}
