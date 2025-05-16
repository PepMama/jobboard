<?php

namespace App\Service;

use App\Entity\Education;
use App\Entity\Users;
use App\Repository\EducationRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;

class EducationService
{
    public function __construct(
        private EducationRepository $educationRepository,
        private StudentRepository   $studentRepository,
        private EntityManagerInterface $em
    ) {}

    public function modelJson(Education $e): array
    {
        return [
            'id'           => $e->getId(),
            'schoolName'   => $e->getSchoolName(),
            'degree'       => $e->getDegree(),
            'fieldOfStudy' => $e->getFieldOfStudy(),
            'startDate'    => $e->getStartDate()?->format('Y-m-d'),
            'endDate'      => $e->getEndDate()?->format('Y-m-d'),
        ];
    }

    /**
     * @return Education|array  Retourne Education ou ['error'=>string]
     */
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
}