<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "Matches")]
class MatchEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_matches", type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Student::class)]
    #[ORM\JoinColumn(name: "student_id", referencedColumnName: "id_student", onDelete: "CASCADE")]
    private ?Student $student = null;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(name: "company_id", referencedColumnName: "id_company", onDelete: "CASCADE")]
    private ?Company $company = null;

    #[ORM\Column(name: "matched_at", type: "datetime")]
    private \DateTimeInterface $matchedAt;

    #[ORM\Column(name: "is_valid", type: "boolean")]
    private bool $isValid = false;

    #[ORM\Column(name: "is_contacted", type: "boolean")]
    private bool $isContacted = false;

    public function __construct() {
        $this->matchedAt = new \DateTime();
    }

    // Getters and setters
    public function getId(): ?int {
        return $this->id;
    }
    public function getStudent(): ?Student {
        return $this->student;
    }
    public function setStudent(?Student $student): self {
        $this->student = $student;
        return $this;
    }
    public function getCompany(): ?Company {
        return $this->company;
    }
    public function setCompany(?Company $company): self {
        $this->company = $company;
        return $this;
    }
    public function getMatchedAt(): \DateTimeInterface {
        return $this->matchedAt;
    }
    public function setMatchedAt(\DateTimeInterface $matchedAt): self {
        $this->matchedAt = $matchedAt;
        return $this;
    }
    public function getIsValid(): bool {
        return $this->isValid;
    }
    public function setIsValid(bool $isValid): self {
        $this->isValid = $isValid;
        return $this;
    }
    public function getIsContacted(): bool {
        return $this->isContacted;
    }
    public function setIsContacted(bool $isContacted): self {
        $this->isContacted = $isContacted;
        return $this;
    }
}
