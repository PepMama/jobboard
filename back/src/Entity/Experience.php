<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "Experiences")]
class Experience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_experience", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "company_name", type: "string", length: 255)]
    private string $companyName;

    #[ORM\Column(name: "job_title", type: "string", length: 255)]
    private string $jobTitle;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: "start_date", type: "datetime", nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(name: "end_date", type: "datetime", nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(targetEntity: Student::class, inversedBy: "experiences")]
    #[ORM\JoinColumn(name: "student_id", referencedColumnName: "id_student", onDelete: "CASCADE")]
    private ?Student $student = null;

    // Getters and setters
    public function getId(): ?int {
        return $this->id;
    }
    public function getCompanyName(): string {
        return $this->companyName;
    }
    public function setCompanyName(string $companyName): self {
        $this->companyName = $companyName;
        return $this;
    }
    public function getJobTitle(): string {
        return $this->jobTitle;
    }
    public function setJobTitle(string $jobTitle): self {
        $this->jobTitle = $jobTitle;
        return $this;
    }
    public function getDescription(): ?string {
        return $this->description;
    }
    public function setDescription(?string $description): self {
        $this->description = $description;
        return $this;
    }
    public function getStartDate(): ?\DateTimeInterface {
        return $this->startDate;
    }
    public function setStartDate(?\DateTimeInterface $startDate): self {
        $this->startDate = $startDate;
        return $this;
    }
    public function getEndDate(): ?\DateTimeInterface {
        return $this->endDate;
    }
    public function setEndDate(?\DateTimeInterface $endDate): self {
        $this->endDate = $endDate;
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
