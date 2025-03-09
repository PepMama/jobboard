<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "Likes_company")]
class LikesCompany
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_like_company", type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Student::class)]
    #[ORM\JoinColumn(name: "student_id", referencedColumnName: "id_student", onDelete: "CASCADE")]
    private ?Student $student = null;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(name: "company_id", referencedColumnName: "id_company", onDelete: "CASCADE")]
    private ?Company $company = null;

    #[ORM\Column(name: "liked_at", type: "datetime")]
    private \DateTimeInterface $likedAt;

    public function __construct() {
        $this->likedAt = new \DateTime();
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
    public function getLikedAt(): \DateTimeInterface {
        return $this->likedAt;
    }
    public function setLikedAt(\DateTimeInterface $likedAt): self {
        $this->likedAt = $likedAt;
        return $this;
    }
}
