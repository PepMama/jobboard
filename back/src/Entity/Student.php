<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "Students")]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_student", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    #[ORM\Column(type: "string", length: 255)]
    private string $firstname;

    #[ORM\Column(type: "string", length: 255)]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    private string $password;

    #[ORM\Column(name: "phone_number", type: "string", length: 255)]
    private string $phoneNumber;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $age = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $address = null;

    #[ORM\Column(name: "postal_code", type: "string", length: 10, nullable: true)]
    private ?string $postalCode = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(type: "text")]
    private string $description;

    #[ORM\Column(name: "created_at", type: "datetime")]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(name: "updated_at", type: "datetime")]
    private \DateTimeInterface $updatedAt;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $linkedin = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $github = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $cv = null;

    // Relations

    #[ORM\OneToMany(mappedBy: "student", targetEntity: Education::class, cascade: ["persist", "remove"])]
    private Collection $educations;

    #[ORM\OneToMany(mappedBy: "student", targetEntity: Experience::class, cascade: ["persist", "remove"])]
    private Collection $experiences;

    #[ORM\ManyToMany(targetEntity: Competency::class)]
    #[ORM\JoinTable(name: "Student_competencies",
        joinColumns: [new ORM\JoinColumn(name: "student_id", referencedColumnName: "id_student", onDelete: "CASCADE")],
        inverseJoinColumns: [new ORM\JoinColumn(name: "competency_id", referencedColumnName: "id_competency", onDelete: "CASCADE")]
    )]
    private Collection $competencies;

    #[ORM\OneToMany(mappedBy: "student", targetEntity: StudentLanguage::class, cascade: ["persist", "remove"])]
    private Collection $studentLanguages;

    #[ORM\OneToOne(mappedBy: "student", targetEntity: SearchPreference::class, cascade: ["persist", "remove"])]
    private ?SearchPreference $searchPreference = null;

    public function __construct()
    {
        $this->educations = new ArrayCollection();
        $this->experiences = new ArrayCollection();
        $this->competencies = new ArrayCollection();
        $this->studentLanguages = new ArrayCollection();
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
    public function getFirstname(): string {
        return $this->firstname;
    }
    public function setFirstname(string $firstname): self {
        $this->firstname = $firstname;
        return $this;
    }
    public function getEmail(): string {
        return $this->email;
    }
    public function setEmail(string $email): self {
        $this->email = $email;
        return $this;
    }
    public function getPassword(): string {
        return $this->password;
    }
    public function setPassword(string $password): self {
        $this->password = $password;
        return $this;
    }
    public function getPhoneNumber(): string {
        return $this->phoneNumber;
    }
    public function setPhoneNumber(string $phoneNumber): self {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }
    public function getAge(): ?int {
        return $this->age;
    }
    public function setAge(?int $age): self {
        $this->age = $age;
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
    public function getPhoto(): ?string {
        return $this->photo;
    }
    public function setPhoto(?string $photo): self {
        $this->photo = $photo;
        return $this;
    }
    public function getDescription(): string {
        return $this->description;
    }
    public function setDescription(string $description): self {
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
    public function getLinkedin(): ?string {
        return $this->linkedin;
    }
    public function setLinkedin(?string $linkedin): self {
        $this->linkedin = $linkedin;
        return $this;
    }
    public function getGithub(): ?string {
        return $this->github;
    }
    public function setGithub(?string $github): self {
        $this->github = $github;
        return $this;
    }
    public function getCv(): ?string {
        return $this->cv;
    }
    public function setCv(?string $cv): self {
        $this->cv = $cv;
        return $this;
    }

    /**
     * @return Collection|Education[]
     */
    public function getEducations(): Collection {
        return $this->educations;
    }
    public function addEducation(Education $education): self {
        if (!$this->educations->contains($education)) {
            $this->educations[] = $education;
            $education->setStudent($this);
        }
        return $this;
    }
    public function removeEducation(Education $education): self {
        if ($this->educations->removeElement($education)) {
            if ($education->getStudent() === $this) {
                $education->setStudent(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Experience[]
     */
    public function getExperiences(): Collection {
        return $this->experiences;
    }
    public function addExperience(Experience $experience): self {
        if (!$this->experiences->contains($experience)) {
            $this->experiences[] = $experience;
            $experience->setStudent($this);
        }
        return $this;
    }
    public function removeExperience(Experience $experience): self {
        if ($this->experiences->removeElement($experience)) {
            if ($experience->getStudent() === $this) {
                $experience->setStudent(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Competency[]
     */
    public function getCompetencies(): Collection {
        return $this->competencies;
    }
    public function addCompetency(Competency $competency): self {
        if (!$this->competencies->contains($competency)) {
            $this->competencies[] = $competency;
        }
        return $this;
    }
    public function removeCompetency(Competency $competency): self {
        $this->competencies->removeElement($competency);
        return $this;
    }

    /**
     * @return Collection|StudentLanguage[]
     */
    public function getStudentLanguages(): Collection {
        return $this->studentLanguages;
    }
    public function addStudentLanguage(StudentLanguage $studentLanguage): self {
        if (!$this->studentLanguages->contains($studentLanguage)) {
            $this->studentLanguages[] = $studentLanguage;
            $studentLanguage->setStudent($this);
        }
        return $this;
    }
    public function removeStudentLanguage(StudentLanguage $studentLanguage): self {
        if ($this->studentLanguages->removeElement($studentLanguage)) {
            if ($studentLanguage->getStudent() === $this) {
                $studentLanguage->setStudent(null);
            }
        }
        return $this;
    }

    public function getSearchPreference(): ?SearchPreference {
        return $this->searchPreference;
    }
    public function setSearchPreference(SearchPreference $searchPreference): self {
        $this->searchPreference = $searchPreference;
        if ($searchPreference->getStudent() !== $this) {
            $searchPreference->setStudent($this);
        }
        return $this;
    }
}
