<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "Educations")]
class Education
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_education", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "school_name", type: "string", length: 255)]
    private string $schoolName;

    #[ORM\Column(type: "string", length: 255)]
    private string $degree;

    #[ORM\Column(name: "field_of_study", type: "string", length: 255)]
    private string $fieldOfStudy;

    #[ORM\Column(name: "start_date", type: "datetime", nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(name: "end_date", type: "datetime", nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(targetEntity: Student::class, inversedBy: "educations")]
    #[ORM\JoinColumn(name: "student_id", referencedColumnName: "id_student", onDelete: "CASCADE")]
    private ?Student $student = null;

    // Getters and setters
    public function getId(): ?int {
        return $this->id;
    }
    public function getSchoolName(): string {
        return $this->schoolName;
    }
    public function setSchoolName(string $schoolName): self {
        $this->schoolName = $schoolName;
        return $this;
    }
    public function getDegree(): string {
        return $this->degree;
    }
    public function setDegree(string $degree): self {
        $this->degree = $degree;
        return $this;
    }
    public function getFieldOfStudy(): string {
        return $this->fieldOfStudy;
    }
    public function setFieldOfStudy(string $fieldOfStudy): self {
        $this->fieldOfStudy = $fieldOfStudy;
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
