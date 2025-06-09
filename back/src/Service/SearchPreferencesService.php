<?php

namespace App\Service;

use App\Entity\Users;
use App\Entity\SearchPreference;
use App\Repository\StudentRepository;
use App\Repository\SearchPreferenceRepository;
use Doctrine\ORM\EntityManagerInterface;

class SearchPreferencesService
{
    private $SearchPreferenceRepository;
    private $studentRepository;
    private $em;

    public function __construct(
        SearchPreferenceRepository $SearchPreferenceRepository,
        StudentRepository $studentRepository,
        EntityManagerInterface $em
    ) {
        $this->SearchPreferenceRepository = $SearchPreferenceRepository;
        $this->studentRepository = $studentRepository;
        $this->em = $em;
    }

    public function modelJson(SearchPreference $preferences): array
    {
        return [
            'id' => $preferences->getId(),
            'field' => $preferences->getField(),
            'contractType' => $preferences->getContractType(),
            'minSalary' => $preferences->getMinSalary(),
            'preferredCity' => $preferences->getPreferredCity(),
            'remote' => $preferences->getRemote(),
            'availability' => $preferences->getAvailability()?->format('Y-m-d')
        ];
    }

    public function managePreferences(Users $user, array $data): SearchPreference|array
    {
        $student = $this->studentRepository->findOneBy(['user' => $user]);
        if (!$student) {
            return ['error' => 'Étudiant introuvable'];
        }

        if (!empty($data['id'])) {
            $preferences = $this->SearchPreferenceRepository->find($data['id']);
            if (!$preferences) {
                return ['error' => 'Préférences non trouvées'];
            }
            if ($preferences->getStudent() !== $student) {
                return ['error' => 'Accès interdit à ces préférences'];
            }
        } else {
            $preferences = new SearchPreference();
            $preferences->setStudent($student);
        }

        $preferences->setField($data['field'] ?? '');
        $preferences->setContractType($data['contractType'] ?? '');
        $preferences->setMinSalary($data['minSalary'] ?? 0);
        $preferences->setPreferredCity($data['preferredCity'] ?? '');
        $preferences->setRemote($data['remote'] ?? false);
        $preferences->setAvailability(new \DateTime($data['availability']));

        $this->em->persist($preferences);
        $this->em->flush();

        return $preferences;
    }

    public function getPreferences(Users $user, int $id): array
    {
        $student = $this->studentRepository->findOneBy(['user' => $user]);
        if (!$student) {
            return ['error' => 'Étudiant introuvable'];
        }

        $preferences = $this->SearchPreferenceRepository->find($id);
        if (!$preferences) {
            return ['error' => 'Préférences introuvables'];
        }

        if ($preferences->getStudent() !== $student) {
            return ['error' => 'Accès interdit à ces préférences'];
        }

        return $this->modelJson($preferences);
    }

    public function getAllPreferences(Users $user): array
    {
        $student = $this->studentRepository->findOneBy(['user' => $user]);
        if (!$student) {
            return ['error' => 'Étudiant introuvable'];
        }

        $preferences = $this->SearchPreferenceRepository->findBy(['student' => $student]);
        return array_map([$this, 'modelJson'], $preferences);
    }

    public function deletePreferences(Users $user, int $id): array
    {
        $student = $this->studentRepository->findOneBy(['user' => $user]);
        if (!$student) {
            return ['error' => 'Étudiant introuvable'];
        }

        $preferences = $this->SearchPreferenceRepository->find($id);
        if (!$preferences) {
            return ['error' => 'Préférences introuvables'];
        }

        if ($preferences->getStudent() !== $student) {
            return ['error' => 'Accès interdit à ces préférences'];
        }

        $this->em->remove($preferences);
        $this->em->flush();

        return ['message' => 'Préférences supprimées avec succès'];
    }
}
