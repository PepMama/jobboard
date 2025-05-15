<?php

namespace App\Service;

use App\Entity\Users;
use App\Entity\Student;
use App\Entity\Experience;
use App\Repository\ExperienceRepository;
use Doctrine\ORM\EntityManagerInterface;

class ExperienceService
{
    private $experienceRepository;
    private $em;

    public function __construct(ExperienceRepository $experienceRepository, EntityManagerInterface $em)
    {
        $this->experienceRepository = $experienceRepository;
        $this->em = $em;
    }

    public function manageExperience(Users $user, array $data, ?int $id = null): Experience
    {
        // Commencer par vérifier si l'utilisateur et l'expérience existent
        // Sinon renvoyer une message d'erreur en json
        // SI oui ajouter la nouvelle expérience
        // Si existe déjà, mettre à jour dans la base de données
    }
}