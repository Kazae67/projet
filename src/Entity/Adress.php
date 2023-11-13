<?php

namespace App\Entity;

use App\Repository\AdressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

#[ORM\Entity(repositoryClass: AdressRepository::class)]
class Adress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @Assert\Choice(choices={"livraison", "facturation"}, message="Invalid address type.")
     */
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * @Assert\NotBlank(message="Street cannot be empty.")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Street should not be longer than {{ limit }} characters."
     * )
     */
    #[ORM\Column(length: 255)]
    private ?string $street = null;

    /**
     * @Assert\NotBlank(message="City cannot be empty.")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "City should not be longer than {{ limit }} characters."
     * )
     */
    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $state = null;

    /**
     * @Assert\NotBlank(message="Postal code cannot be empty.")
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Postal code should not be longer than {{ limit }} characters."
     * )
     */
    #[ORM\Column(length: 10)]
    private ?string $postalCode = null;

    /**
     * @Assert\NotBlank(message="Country cannot be empty.")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Country should not be longer than {{ limit }} characters."
     * )
     */
    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(type: "boolean")]
    private $isDefaultBilling = false;
    #[ORM\Column(type: "boolean")]
    private $isDefaultDelivery = false;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "addresses")]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
    public function getIsDefaultBilling(): bool
    {
        return $this->isDefaultBilling;
    }

    public function setIsDefaultBilling(bool $isDefaultBilling): self
    {
        $this->isDefaultBilling = $isDefaultBilling;
        return $this;
    }

    public function getIsDefaultDelivery(): bool
    {
        return $this->isDefaultDelivery;
    }

    public function setIsDefaultDelivery(bool $isDefaultDelivery): self
    {
        $this->isDefaultDelivery = $isDefaultDelivery;
        return $this;
    }

    /**
     * Met à jour cette adresse avec les informations d'une autre adresse.
     *
     * @param Adress $other L'autre adresse à utiliser pour la mise à jour.
     */
    public function updateFromOther(Adress $other): void
    {
        // Mettez à jour les champs souhaités
        $this->street = $other->getStreet();
        $this->city = $other->getCity();
        $this->postalCode = $other->getPostalCode();
        $this->country = $other->getCountry();
        $this->state = $other->getState();
    }

}
