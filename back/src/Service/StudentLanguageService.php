<?php

namespace App\Service;

use App\Entity\StudentLanguage;
use App\Entity\Users;
use App\Repository\StudentLanguageRepository;
use App\Repository\StudentRepository;
use App\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;

class StudentLanguageService
{
    private StudentLanguageRepository $studentLanguageRepo;
    private StudentRepository $studentRepo;
    private LanguageRepository $languageRepo;
    private EntityManagerInterface $em;

    public function __construct(
        StudentLanguageRepository $studentLanguageRepo,
        StudentRepository $studentRepo,
        LanguageRepository $languageRepo,
        EntityManagerInterface $em
    ) {
        $this->studentLanguageRepo = $studentLanguageRepo;
        $this->studentRepo = $studentRepo;
        $this->languageRepo = $languageRepo;
        $this->em = $em;
    }

    public function addLanguage(Users $user, array $data): StudentLanguage|array
    {
        $student = $this->studentRepo->findOneBy(['user' => $user]);
        if (!$student) return ['error' => 'Étudiant introuvable'];

        $language = $this->languageRepo->find($data['languageId'] ?? null);
        if (!$language) return ['error' => 'Langue introuvable'];

        $existing = $this->studentLanguageRepo->findOneBy([
            'student' => $student,
            'language' => $language
        ]);
        if ($existing) return ['error' => 'Langue déjà associée à cet étudiant'];

        $studentLanguage = new StudentLanguage();
        $studentLanguage->setStudent($student);
        $studentLanguage->setLanguage($language);
        $studentLanguage->setProficiency($data['proficiency'] ?? 'Beginner');

        $this->em->persist($studentLanguage);
        $this->em->flush();

        return $studentLanguage;
    }

    public function deleteLanguage(Users $user, int $languageId): array
    {
        $student = $this->studentRepo->findOneBy(['user' => $user]);
        if (!$student) return ['error' => 'Étudiant introuvable'];

        $language = $this->languageRepo->find($languageId);
        if (!$language) return ['error' => 'Langue introuvable'];

        $studentLanguage = $this->studentLanguageRepo->findOneBy([
            'student' => $student,
            'language' => $language
        ]);
        if (!$studentLanguage) return ['error' => 'Langue non liée à cet étudiant'];

        $this->em->remove($studentLanguage);
        $this->em->flush();

        return ['message' => 'Langue supprimée'];
    }

    public function getLanguages(Users $user): array
    {
        $student = $this->studentRepo->findOneBy(['user' => $user]);
        if (!$student) return ['error' => 'Étudiant introuvable'];

        $list = $this->studentLanguageRepo->findBy(['student' => $student]);

        return array_map(function (StudentLanguage $sl) {
            return [
                'languageId' => $sl->getLanguage()->getId(),
                'language' => $sl->getLanguage()->getName(),
                'proficiency' => $sl->getProficiency()
            ];
        }, $list);
    }
}
