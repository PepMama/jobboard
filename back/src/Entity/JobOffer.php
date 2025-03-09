<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "Job_offers")]
class JobOffer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_job", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50)]
    private string $state;

    #[ORM\Column(type: "string", length: 255)]
    private string $title;

    #[ORM\Column(type: "text")]
    private string $description;

    #[ORM\Column(name: "contract_type", type: "string", length: 255, columnDefinition: "ENUM('Stage', 'Alternance', 'CDI', 'CDD')")]
    private string $contractType;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $salary = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(type: "boolean")]
    private bool $remote;

    #[ORM\Column(name: "start_date", type: "date")]
    private \DateTimeInterface $startDate;

    #[ORM\Column(name: "created_at", type: "datetime")]
    private \DateTimeInterface $createdAt;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(name: "company_id", referencedColumnName: "id_company", onDelete: "CASCADE")]
    private ?Company $company = null;

    public function __construct() {
        $this->createdAt = new \DateTime();
    }

    // Getters and setters
    public function getId(): ?int {
        return $this->id;
    }
    public function getState(): string {
        return $this->state;
    }
    public function setState(string $state): self {
        $this->state = $state;
        return $this;
    }
    public function getTitle(): string {
        return $this->title;
    }
    public function setTitle(string $title): self {
        $this->title = $title;
        return $this;
    }
    public function getDescription(): string {
        return $this->description;
    }
    public function setDescription(string $description): self {
        $this->description = $description;
        return $this;
    }
    public function getContractType(): string {
        return $this->contractType;
    }
    public function setContractType(string $contractType): self {
        $this->contractType = $contractType;
        return $this;
    }
    public function getSalary(): ?int {
        return $this->salary;
    }
    public function setSalary(?int $salary): self {
        $this->salary = $salary;
        return $this;
    }
    public function getCity(): ?string {
        return $this->city;
    }
    public function setCity(?string $city): self {
        $this->city = $city;
        return $this;
    }
    public function getRemote(): bool {
        return $this->remote;
    }
    public function setRemote(bool $remote): self {
        $this->remote = $remote;
        return $this;
    }
    public function getStartDate(): \DateTimeInterface {
        return $this->startDate;
    }
    public function setStartDate(\DateTimeInterface $startDate): self {
        $this->startDate = $startDate;
        return $this;
    }
    public function getCreatedAt(): \DateTimeInterface {
        return $this->createdAt;
    }
    public function setCreatedAt(\DateTimeInterface $createdAt): self {
        $this->createdAt = $createdAt;
        return $this;
    }
    public function getCompany(): ?Company {
        return $this->company;
    }
    public function setCompany(?Company $company): self {
        $this->company = $company;
        return $this;
    }
}
