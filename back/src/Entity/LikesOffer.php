<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "Likes_offer")]
class LikesOffer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_like_offer", type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Student::class)]
    #[ORM\JoinColumn(name: "student_id", referencedColumnName: "id_student", onDelete: "CASCADE")]
    private ?Student $student = null;

    #[ORM\ManyToOne(targetEntity: JobOffer::class)]
    #[ORM\JoinColumn(name: "job_id", referencedColumnName: "id_job", onDelete: "CASCADE")]
    private ?JobOffer $jobOffer = null;

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

    public function getJobOffer(): ?JobOffer {
        return $this->jobOffer;
    }

    public function setJobOffer(?JobOffer $jobOffer): self {
        $this->jobOffer = $jobOffer;
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
