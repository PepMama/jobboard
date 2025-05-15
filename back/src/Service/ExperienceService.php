<?php

namespace App\Service;

use App\Entity\Users;
use App\Entity\Student;
use App\Entity\Experience;
use App\Repository\ExperienceRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;

class ExperienceService
{
    private $experienceRepository;
    private $studentRepository;
    private $em;

    public function __construct(ExperienceRepository $experienceRepository, EntityManagerInterface $em, StudentRepository $studentRepository)
    {
        $this->experienceRepository = $experienceRepository;
        $this->studentRepository = $studentRepository;
        $this->em = $em;
    }

    public function manageExperience(Users $user, array $data): Experience|array
    {
        $student = $this->studentRepository->findOneBy(['user' => $user]);
        if(!$student){
            return ['error' => 'L\'étudiant n\'existe pas !'];
        }

        if (!empty($data['id'])) {
            $experience = $this->experienceRepository->find($data['id']);
    
            if (!$experience) {
                return ['error' => 'Expérience non trouvée pour mise à jour'];
            }

            if ($experience->getStudent() !== $student) {
                return ['error' => 'Accès interdit à cette expérience'];
            }
        } else {
            $experience = new Experience();
            $experience->setStudent($student);
        }

        $experience->setCompanyName($data['companyName'] ?? '');
        $experience->setJobTitle($data['jobTitle'] ?? '');
        $experience->setDescription($data['description'] ?? '');
        $experience->setStartDate(new \DateTime($data['startDate']));
        $experience->setEndDate(new \DateTime($data['endDate']));

        $this->em->persist($experience);
        $this->em->flush();

        return $experience;
    }

    public function deleteExperience(Users $user, int $id): array
    {
        $student = $this->studentRepository->findOneBy(['user' => $user]);
        if (!$student) {
            return ['error' => 'Étudiant introuvable'];
        }

        $experience = $this->experienceRepository->find($id);
        if (!$experience) {
            return ['error' => 'Expérience introuvable'];
        }

        if ($experience->getStudent() !== $student) {
            return ['error' => 'Accès refusé à cette expérience'];
        }

        $this->em->remove($experience);
        $this->em->flush();

        return ['message' => 'Expérience supprimée avec succès'];
    }

    public function getExperience(Users $user, int $id): array
    {
        $student = $this->studentRepository->findOneBy(['user' => $user]);
        if (!$student) {
            return ['error' => 'Étudiant introuvable'];
        }
    
        $experience = $this->experienceRepository->find($id);
        if (!$experience) {
            return ['error' => 'Expérience introuvable'];
        }
    
        if ($experience->getStudent() !== $student) {
            return ['error' => 'Accès interdit à cette expérience'];
        }

        return $this->modelJson($experience);
    }

    public function getAllExperiences(Users $user): array
    {
        $student = $this->studentRepository->findOneBy(['user' => $user]);
        if (!$student) {
            return ['error' => 'Étudiant introuvable'];
        }

        $experiences = $this->experienceRepository->findBy(['student' => $student]);

        return array_map([$this, 'modelJson'], $experiences);
    }

    public function modelJson(Experience $experience): array
    {
        return [
            'id' => $experience->getId(),
            'companyName' => $experience->getCompanyName(),
            'jobTitle' => $experience->getJobTitle(),
            'description' => $experience->getDescription(),
            'startDate' => $experience->getStartDate()?->format('Y-m-d'),
            'endDate' => $experience->getEndDate()?->format('Y-m-d'),
        ];
    }


}