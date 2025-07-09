<?php

namespace App\Service;

use App\Entity\Users;
use App\Entity\Student;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;

class StudentService
{
    private $studentRepository;
    private $em;

    public function __construct(StudentRepository $studentRepository, EntityManagerInterface $em)
    {
        $this->studentRepository = $studentRepository;
        $this->em = $em;
    }

    public function getStudentByUser(Users $user): ?Student
    {
        return $this->studentRepository->findOneBy(['user' => $user]);
    }

    public function getAllStudent(): array
    {
        return $this->studentRepository->findAll();
    }

    public function completeProfileService(Users $user, array $data): Student
    {
        // On vérifie si l'étudiant existe déjà
        $student = $this->studentRepository->findOneBy(['user' => $user]);

        if(!$student){
            $student = new Student();
            $student->setUser($user);
            $student->setCreatedAt(new \DateTime());
        }

        // Mise à jour des infos
        $student->setName($data['name']);
        $student->setFirstname($data['firstname']);
        $student->setPhoneNumber($data['phone_number']);
        $student->setAge($data['age'] ?? null);
        $student->setCity($data['city'] ?? null);
        $student->setAddress($data['address'] ?? null);
        $student->setPostalCode($data['postal_code'] ?? null);
        $student->setPhoto($data['photo'] ?? null);
        $student->setDescription($data['description']);
        $student->setLinkedin($data['linkedin'] ?? null);
        $student->setGithub($data['github'] ?? null);
        $student->setCv($data['cv'] ?? null);
        $student->setUpdatedAt(new \DateTime());

        $this->em->persist($student);
        $this->em->flush();

        return $student;
    }
    
    public function save(Student $student): void
    {
        $this->em->persist($student);
        $this->em->flush();
    }

    public function getStudentByName(string $name): ?Student
    {
        return $this->studentRepository->findOneBy(['name' => $name]);
    }
}