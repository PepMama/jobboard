<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    // public function findByUserId(int $userId): ?Student
    // {
    //     return $this->createQueryBuilder('s')
    //         ->andWhere('s.user = :userId')
    //         ->setParameter('userId', $userId)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }
}
