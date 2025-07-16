<?php

namespace App\Service;

use App\Entity\MatchEntity;
use App\Entity\LikesOffer;
use App\Entity\LikesStudent;
use App\Entity\Notification;
use App\Entity\Student;
use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;

class MatchService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function tryToCreateMatch(Student $student, Company $company): ?MatchEntity
{
    $existing = $this->em->getRepository(MatchEntity::class)->findOneBy([
        'student' => $student,
        'company' => $company,
    ]);
    if ($existing) return null;

    $studentLike = $this->em->getRepository(LikesOffer::class)->findOneBy([
        'student' => $student
    ]);

    if (!$studentLike || $studentLike->getJobOffer()->getCompany()->getId() !== $company->getId()) return null;

    $companyLike = $this->em->getRepository(LikesStudent::class)->findOneBy([
        'student' => $student,
        'company' => $company
    ]);

    if (!$companyLike) return null;

    $match = new MatchEntity();
    $match->setStudent($student);
    $match->setCompany($company);
    $match->setMatchedAt(new \DateTimeImmutable());
    $match->setIsValid(true);
    $match->setIsContacted(false);

    $this->em->persist($match);
    $this->em->flush();
    return $match;
}
}