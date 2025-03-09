<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "Student_languages")]
class StudentLanguage
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Student::class, inversedBy: "studentLanguages")]
    #[ORM\JoinColumn(name: "student_id", referencedColumnName: "id_student", onDelete: "CASCADE")]
    private ?Student $student = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Language::class)]
    #[ORM\JoinColumn(name: "language_id", referencedColumnName: "id_language", onDelete: "CASCADE")]
    private ?Language $language = null;

    #[ORM\Column(type: "string", length: 20, columnDefinition: "ENUM('Beginner', 'Intermediate', 'Advanced', 'Fluent')")]
    private string $proficiency;

    // Getters and setters
    public function getStudent(): ?Student {
        return $this->student;
    }
    public function setStudent(?Student $student): self {
        $this->student = $student;
        return $this;
    }
    public function getLanguage(): ?Language {
        return $this->language;
    }
    public function setLanguage(?Language $language): self {
        $this->language = $language;
        return $this;
    }
    public function getProficiency(): string {
        return $this->proficiency;
    }
    public function setProficiency(string $proficiency): self {
        $this->proficiency = $proficiency;
        return $this;
    }
}
