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

    //  public function getStudentsForCompany(?string $keyword = null, ?string $city = null, ?int $studentId = null): array
    // {
    //     $qb = $this->em
    //         ->getRepository(StudentRepository::class)
    //         ->createQueryBuilder('o')
    //         ->join('o.', 'c')
    //         ->addSelect('c')
    //         ->where('o.state = :state')
    //         ->setParameter('state', 'active');

    //     if ($keyword) {
    //         $qb->andWhere('LOWER(o.title) LIKE :keyword OR LOWER(o.description) LIKE :keyword')
    //             ->setParameter('keyword', '%' . strtolower($keyword) . '%');
    //     }

    //     if ($city) {
    //         $qb->andWhere('LOWER(o.city) LIKE :city')
    //             ->setParameter('city', '%' . strtolower($city) . '%');
    //     }

    //     // Exclure les offres likées par l'étudiant
    //     if ($studentId !== null) {
    //         $subQb = $this->em->createQueryBuilder()
    //             ->select('IDENTITY(lo.jobOffer)')
    //             ->from('App\Entity\LikesOffer', 'lo')
    //             ->where('lo.student = :studentId');

    //         $qb->andWhere($qb->expr()->notIn('o.id', $subQb->getDQL()))
    //             ->setParameter('studentId', $studentId);
    //     }

    //     $offers = $qb->getQuery()->getResult();

    //     return array_map(function (JobOffer $offer) {
    //         $company = $offer->getCompany();
    //         return [
    //             'id' => $offer->getId(),
    //             'title' => $offer->getTitle(),
    //             'contractType' => $offer->getContractType(),
    //             'city' => $offer->getCity(),
    //             'company' => [
    //                 'name' => $company->getName(),
    //                 'logo' => $company->getLogo(),
    //             ]
    //         ];
    //     }, $offers);
    // }
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
}