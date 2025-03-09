<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "Search_preferences")]
class SearchPreference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_preferences", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $field;

    #[ORM\Column(name: "contract_type", type: "string", length: 255, nullable: true, columnDefinition: "ENUM('Stage', 'Alternance', 'CDI', 'CDD')")]
    private ?string $contractType = null;

    #[ORM\Column(name: "min_salary", type: "integer", nullable: true)]
    private ?int $minSalary = null;

    #[ORM\Column(name: "preferred_city", type: "string", length: 255)]
    private string $preferredCity;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $remote = null;

    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTimeInterface $availability = null;

    #[ORM\OneToOne(targetEntity: Student::class, inversedBy: "searchPreference")]
    #[ORM\JoinColumn(name: "student_id", referencedColumnName: "id_student", onDelete: "CASCADE")]
    private ?Student $student = null;

    // Getters and setters
    public function getId(): ?int {
        return $this->id;
    }
    public function getField(): string {
        return $this->field;
    }
    public function setField(string $field): self {
        $this->field = $field;
        return $this;
    }
    public function getContractType(): ?string {
        return $this->contractType;
    }
    public function setContractType(?string $contractType): self {
        $this->contractType = $contractType;
        return $this;
    }
    public function getMinSalary(): ?int {
        return $this->minSalary;
    }
    public function setMinSalary(?int $minSalary): self {
        $this->minSalary = $minSalary;
        return $this;
    }
    public function getPreferredCity(): string {
        return $this->preferredCity;
    }
    public function setPreferredCity(string $preferredCity): self {
        $this->preferredCity = $preferredCity;
        return $this;
    }
    public function getRemote(): ?bool {
        return $this->remote;
    }
    public function setRemote(?bool $remote): self {
        $this->remote = $remote;
        return $this;
    }
    public function getAvailability(): ?\DateTimeInterface {
        return $this->availability;
    }
    public function setAvailability(?\DateTimeInterface $availability): self {
        $this->availability = $availability;
        return $this;
    }
    public function getStudent(): ?Student {
        return $this->student;
    }
    public function setStudent(?Student $student): self {
        $this->student = $student;
        return $this;
    }
}
