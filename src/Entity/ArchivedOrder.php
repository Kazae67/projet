<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class ArchivedOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $archivedAt;

    // Informations sur l'utilisateur
    #[ORM\Column(type: 'string', length: 255)]
    private string $userName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $lastName;

    // Informations sur l'adresse
    #[ORM\Column(type: 'text')]
    private string $addressDetails;

    // Total de la commande
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $total;

    // Status de la commande archivée
    #[ORM\Column(type: 'string', length: 255)]
    private string $status;

    // Date de création de la commande archivée
    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    // Collection des détails de la commande
    #[ORM\OneToMany(mappedBy: 'archivedOrder', targetEntity: ArchivedOrderDetail::class, cascade: ['persist'])]
    private Collection $archivedOrderDetails;

    public function __construct()
    {
        $this->archivedAt = new \DateTime();
        $this->archivedOrderDetails = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArchivedAt(): \DateTimeInterface
    {
        return $this->archivedAt;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;
        return $this;
    }

    public function getAddressDetails(): string
    {
        return $this->addressDetails;
    }

    public function setAddressDetails(string $addressDetails): self
    {
        $this->addressDetails = $addressDetails;
        return $this;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return Collection|ArchivedOrderDetail[]
     */
    public function getArchivedOrderDetails(): Collection
    {
        return $this->archivedOrderDetails;
    }

    public function addArchivedOrderDetail(ArchivedOrderDetail $archivedOrderDetail): self
    {
        if (!$this->archivedOrderDetails->contains($archivedOrderDetail)) {
            $this->archivedOrderDetails[] = $archivedOrderDetail;
            $archivedOrderDetail->setArchivedOrder($this);
        }
        return $this;
    }

    public function removeArchivedOrderDetail(ArchivedOrderDetail $archivedOrderDetail): self
    {
        if ($this->archivedOrderDetails->removeElement($archivedOrderDetail)) {
            // set the owning side to null (unless already changed)
            if ($archivedOrderDetail->getArchivedOrder() === $this) {
                $archivedOrderDetail->setArchivedOrder(null);
            }
        }
        return $this;
    }

}