<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
#[ORM\Table(name:"Users")]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string")]
    private ?string $username = null;

    #[ORM\Column(type:"string")]
    private ?string $email = null;

    #[ORM\Column(type:"string")]
    private ?string $password = null;

    // Définir le role des utilisateurs : companie ou etudiant
    #[ORM\Column(type:"string")]
    private ?string $role = null;

    #[ORM\OneToOne(targetEntity: Student::class, mappedBy: "user", cascade: ["persist", "remove"])]
    private ?Student $student = null;

    #[ORM\OneToOne(targetEntity: Company::class, mappedBy: "user", cascade: ["persist", "remove"])]
    private ?Company $company = null;

    public function getId(): ?int{
        return $this->id;
    }

    public function getUsername(): ?string {
        return $this->username;
    }

    public function setUsername(string $username): self {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(string $email): self {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setPassword(string $password): self {
        $this->password = $password;
        return $this;
    }

    public function getRole(): ?string {
        return $this->role;
    }

    public function setRole(string $role): self {
        $this->role = $role;
        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        if ($student !== null && $student->getUser() !== $this) {
            $student->setUser($this);
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        if ($company !== null && $company->getUser() !== $this) {
            $company->setUser($this);
        }

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        // utile si tu stockes un plainPassword temporairement, sinon vide
    }
}