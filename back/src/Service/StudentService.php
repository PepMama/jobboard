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

        if (!$student) {
            $student = new Student();
            $student->setUser($user);
            $student->setCreatedAt(new \DateTime());
        }

        // Mise à jour des infos
        $student->setName($data['name']);
        $student->setFirstname($data['firstname']);
        $student->setPhoneNumber($data['phone_number']);
        $age = $data['age'] ?? null;
        if (is_string($age) && is_numeric($age)) {
            $age = (int)$age;
        }
        $student->setAge($age);
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
    public function getStudentsForCompany(?string $keyword, ?string $city, ?string $degree, ?string $fieldOfStudy, ?int $companyId = null): array
    {
        $qb = $this->em
            ->getRepository(Student::class)
            ->createQueryBuilder('s')
            ->leftJoin('s.educations', 'e')
            ->where('1 = 1');

        if ($keyword) {
            $qb->andWhere('s.description LIKE :keyword')
                ->setParameter('keyword', '%' . $keyword . '%');
        }

        if ($city) {
            $qb->andWhere('s.city = :city')
                ->setParameter('city', $city);
        }

        if ($degree) {
            $qb->andWhere('e.degree LIKE :degree')
                ->setParameter('degree', '%' . $degree . '%');
        }

        if ($fieldOfStudy) {
            $qb->andWhere('e.fieldOfStudy LIKE :fieldOfStudy')
                ->setParameter('fieldOfStudy', '%' . $fieldOfStudy . '%');
        }
        if ($companyId !== null) {
            $subQb = $this->em->createQueryBuilder()
                ->select('IDENTITY(ls.student)')
                ->from('App\Entity\LikesStudent', 'ls')
                ->where('ls.company = :companyId');

            $qb->andWhere($qb->expr()->notIn('s.id', $subQb->getDQL()))
                ->setParameter('companyId', $companyId);
        }

        $students = $qb->getQuery()->getResult();
        return array_map(fn($student) => $student->toArray(), $students);
    }


    public function save(Student $student): void
    {
        $this->em->persist($student);
        $this->em->flush();
    }

    public function getStudentByName(string $fullName): ?Student
    {
        [$firstname, $lastname] = array_pad(explode(' ', trim($fullName), 2), 2, null);
        return $this->studentRepository->findOneBy([
            'firstname' => $firstname,
            'name' => $lastname,
        ]);
    }


}