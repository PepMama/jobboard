<?php

namespace App\Service;

use App\Entity\Users;
use App\Entity\Competency;
use App\Repository\CompetencyRepository;
use Doctrine\ORM\EntityManagerInterface;

class StudentCompetencyService
{
    private $em;
    private $competencyRepo;

    public function __construct(EntityManagerInterface $em, CompetencyRepository $competencyRepo)
    {
        $this->em = $em;
        $this->competencyRepo = $competencyRepo;
    }

    public function getCompetencies(Users $user): array
    {
        $student = $user->getStudent();
        if (!$student) return [];
        return array_map(fn($c) => [
            'id' => $c->getId(),
            'name' => $c->getName()
        ], $student->getCompetencies()->toArray());
    }

    public function addCompetency(Users $user, array $data): array
    {
        $student = $user->getStudent();
        if (!$student) return ['error' => 'Étudiant non trouvé'];
        $name = trim($data['name'] ?? '');
        if (!$name) return ['error' => 'Nom de compétence requis'];
        // Vérifier si la compétence existe déjà
        $competency = $this->competencyRepo->findOneBy(['name' => $name]);
        if (!$competency) {
            $competency = new Competency();
            $competency->setName($name);
            $this->em->persist($competency);
        }
        if ($student->getCompetencies()->contains($competency)) {
            return ['error' => 'Compétence déjà ajoutée'];
        }
        $student->addCompetency($competency);
        $this->em->persist($student);
        $this->em->flush();
        return ['id' => $competency->getId(), 'name' => $competency->getName()];
    }

    public function deleteCompetency(Users $user, int $competencyId): array
    {
        $student = $user->getStudent();
        if (!$student) return ['error' => 'Étudiant non trouvé'];
        $competency = $this->competencyRepo->find($competencyId);
        if (!$competency) return ['error' => 'Compétence non trouvée'];
        if (!$student->getCompetencies()->contains($competency)) {
            return ['error' => 'Compétence non liée à cet étudiant'];
        }
        $student->removeCompetency($competency);
        $this->em->persist($student);
        $this->em->flush();
        return ['message' => 'Compétence supprimée'];
    }
} 