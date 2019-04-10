<?php

namespace App\Entity\Gui;

use App\Entity\Gui\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PurchaseRepository")
 */
class Purchase
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Gui\User", inversedBy="purchases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $timestamp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $service_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $service_description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $request_url;

    /**
     * @ORM\Column(type="integer")
     */
    private $order_number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $product_sku;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $product_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $product_quantity;

    /**
     * @ORM\Column(type="integer")
     */
    private $product_price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $product_currency;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $callback_url;

    public function getId(): ?int
    {
        return $this->id;
        
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTimestamp(): ?string
    {
        return $this->timestamp;
    }

    public function setTimestamp(?string $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getServiceId(): ?string
    {
        return $this->service_id;
    }

    public function setServiceId(string $service_id): self
    {
        $this->service_id = $service_id;

        return $this;
    }

    public function getServiceDescription(): ?string
    {
        return $this->service_description;
    }

    public function setServiceDescription(string $service_description): self
    {
        $this->service_description = $service_description;

        return $this;
    }

    public function getRequestUrl(): ?string
    {
        return $this->request_url;
    }

    public function setRequestUrl(string $request_url): self
    {
        $this->request_url = $request_url;

        return $this;
    }

    public function getOrderNumber(): ?int
    {
        return $this->order_number;
    }

    public function setOrderNumber(int $order_number): self
    {
        $this->order_number = $order_number;

        return $this;
    }

    public function getProductSku(): ?string
    {
        return $this->product_sku;
    }

    public function setProductSku(string $product_sku): self
    {
        $this->product_sku = $product_sku;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->product_name;
    }

    public function setProductName(string $product_name): self
    {
        $this->product_name = $product_name;

        return $this;
    }

    public function getProductQuantity(): ?int
    {
        return $this->product_quantity;
    }

    public function setProductQuantity(int $product_quantity): self
    {
        $this->product_quantity = $product_quantity;

        return $this;
    }

    public function getProductPrice(): ?int
    {
        return $this->product_price;
    }

    public function setProductPrice(int $product_price): self
    {
        $this->product_price = $product_price;

        return $this;
    }

    public function getProductCurrency(): ?string
    {
        return $this->product_currency;
    }

    public function setProductCurrency(string $product_currency): self
    {
        $this->product_currency = $product_currency;

        return $this;
    }

    public function getCallbackUrl(): ?string
    {
        return $this->callback_url;
    }

    public function setCallbackUrl(?string $callback_url): self
    {
        $this->callback_url = $callback_url;

        return $this;
    }
}
