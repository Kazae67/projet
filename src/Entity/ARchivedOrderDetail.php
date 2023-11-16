<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ArchivedOrderDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ArchivedOrder::class, inversedBy: 'archivedOrderDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ArchivedOrder $archivedOrder = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $productName;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $price;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArchivedOrder(): ?ArchivedOrder
    {
        return $this->archivedOrder;
    }

    public function setArchivedOrder(?ArchivedOrder $archivedOrder): self
    {
        $this->archivedOrder = $archivedOrder;
        return $this;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }
}
