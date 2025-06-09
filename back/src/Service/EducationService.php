<?php

namespace App\Service;

use App\Entity\Education;
use App\Entity\Users;
use App\Repository\EducationRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;

class EducationService
{
    private $educationRepository;
    private $studentRepository;
    private $em;

    public function __construct(
        EducationRepository $educationRepository,
        StudentRepository $studentRepository,
        EntityManagerInterface $em
    ) {
        $this->educationRepository = $educationRepository;
        $this->studentRepository = $studentRepository;
        $this->em = $em;
    }

    public function modelJson(Education $education): array
    {
        return [
            'id' => $education->getId(),
            'schoolName' => $education->getSchoolName(),
            'degree' => $education->getDegree(),
            'fieldOfStudy' => $education->getFieldOfStudy(),
            'startDate' => $education->getStartDate()?->format('Y-m-d'),
            'endDate' => $education->getEndDate()?->format('Y-m-d'),
        ];
    }

    public function manageEducation(Users $user, array $data): Education|array
    {
        $student = $this->studentRepository->findOneBy(['user' => $user]);
        if (!$student) {
            return ['error' => 'L\'étudiant n\'existe pas !'];
        }

        if (!empty($data['id'])) {
            $education = $this->educationRepository->find($data['id']);
            if (!$education) {
                return ['error' => 'Diplôme non trouvé'];
            }
            if ($education->getStudent() !== $student) {
                return ['error' => 'Accès interdit à ce diplôme'];
            }
        } else {
            $education = new Education();
            $education->setStudent($student);
        }

        $education->setSchoolName($data['schoolName'] ?? '');
        $education->setDegree($data['degree'] ?? '');
        $education->setFieldOfStudy($data['fieldOfStudy'] ?? '');
        $education->setStartDate(new \DateTime($data['startDate']));
        $education->setEndDate(new \DateTime($data['endDate']));
        
        $this->em->persist($education);
        $this->em->flush();

        return $education;
    }

    public function deleteEducation(Users $user, int $id): array
    {
        $student = $this->studentRepository->findOneBy(['user' => $user]);
        if(!$student){
            return ['error' => 'Étudiant introuvable'];
        }

        $education = $this->educationRepository->find($id);
        if(!$education){
            return ['error' => 'Education introuvable'];
        }

        if($education->getStudent() !== $student){
            return ['error' => 'Accès refusé à cette éducation'];
        }

        $this->em->remove($education);
        $this->em->flush();

        return ['message' => 'Education supprimée avec succès'];
    }

    public function getEducation(Users $user, int $id): array
    {
        $student = $this->studentRepository->findOneBy(['user' => $user]);
        if (!$student) {
            return ['error' => 'Étudiant introuvable'];
        }

        $education = $this->educationRepository->find($id);
        if (!$education) {
            return ['error' => 'Éducation introuvable'];
        }

        if ($education->getStudent() !== $student) {
            return ['error' => 'Accès interdit à cette éducation'];
        }

        return $this->modelJson($education);
    }

    public function getAllEducations(Users $user): array
    {
        $student = $this->studentRepository->findOneBy(['user' => $user]);
        if (!$student) {
            return ['error' => 'Étudiant introuvable'];
        }

        $educations = $this->educationRepository->findBy(['student' => $student]);
        return array_map([$this, 'modelJson'], $educations);
    }

}