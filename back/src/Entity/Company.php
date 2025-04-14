<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "Companies")]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_company", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    #[ORM\Column(name: "phone_number", type: "string", length: 20)]
    private string $phoneNumber;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $linkedin = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $industry = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $address = null;

    #[ORM\Column(name: "postal_code", type: "string", length: 10, nullable: true)]
    private ?string $postalCode = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: "created_at", type: "datetime")]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(name: "updated_at", type: "datetime")]
    private \DateTimeInterface $updatedAt;

    #[ORM\OneToOne(inversedBy: "company", targetEntity: Users::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", onDelete: "CASCADE", nullable: false)]
    private ?Users $user = null;

    public function __construct() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    // Getters and setters
    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }
    
    public function getPhoneNumber(): string {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getWebsite(): ?string {
        return $this->website;
    }

    public function setWebsite(?string $website): self {
        $this->website = $website;
        return $this;
    }

    public function getLinkedin(): ?string {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self {
        $this->linkedin = $linkedin;
        return $this;
    }

    public function getLogo(): ?string {
        return $this->logo;
    }

    public function setLogo(?string $logo): self {
        $this->logo = $logo;
        return $this;
    }

    public function getIndustry(): ?string {
        return $this->industry;
    }

    public function setIndustry(?string $industry): self {
        $this->industry = $industry;
        return $this;
    }

    public function getCity(): ?string {
        return $this->city;
    }

    public function setCity(?string $city): self {
        $this->city = $city;
        return $this;
    }

    public function getAddress(): ?string {
        return $this->address;
    }

    public function setAddress(?string $address): self {
        $this->address = $address;
        return $this;
    }

    public function getPostalCode(): ?string {
        return $this->postalCode;
    }
    
    public function setPostalCode(?string $postalCode): self {
        $this->postalCode = $postalCode;
        return $this;
    }
    
    public function getDescription(): ?string {
        return $this->description;
    }
    
    public function setDescription(?string $description): self {
        $this->description = $description;
        return $this;
    }
    
    public function getCreatedAt(): \DateTimeInterface {
        return $this->createdAt;
    }
    
    public function setCreatedAt(\DateTimeInterface $createdAt): self {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    public function getUpdatedAt(): \DateTimeInterface {
        return $this->updatedAt;
    }
    
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;
        return $this;
    }
}
