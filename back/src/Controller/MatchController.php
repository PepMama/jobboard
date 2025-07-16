<?php

namespace App\Controller;

use App\Entity\MatchEntity;
use App\Entity\Student;
use App\Entity\Company;
use App\Service\StudentService;
use App\Service\CompanyService;
use App\Service\TokenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/matches')]
class MatchController extends AbstractController
{
    #[Route('/count', name: 'app_match_count', methods: ['GET'])]
    public function getMatchCount(
        Request $request,
        TokenService $tokenService,
        StudentService $studentService,
        CompanyService $companyService,
        EntityManagerInterface $em
    ): JsonResponse {
        $user = $tokenService->getUserFromRequest($request);

        $student = $studentService->getStudentByUser($user);
        $company = $companyService->getCompanyByUser($user);

        if ($student) {
            $count = $em->getRepository(MatchEntity::class)->count([
                'student' => $student,
                'isValid' => true
            ]);
        } elseif ($company) {
            $count = $em->getRepository(MatchEntity::class)->count([
                'company' => $company,
                'isValid' => true
            ]);
        } else {
            return new JsonResponse(['error' => 'Utilisateur inconnu'], 400);
        }

        return new JsonResponse(['matchCount' => $count]);
    }

    #[Route('', name: 'app_matches', methods: ['GET'])]
    public function getMatches(
        Request $request,
        TokenService $tokenService,
        EntityManagerInterface $em
    ): JsonResponse {
        $user = $tokenService->getUserFromRequest($request);

        $student = $em->getRepository(Student::class)->findOneBy(['user' => $user]);
        $company = $em->getRepository(Company::class)->findOneBy(['user' => $user]);

        $criteria = ['isValid' => true];
        if ($student) {
            $criteria['student'] = $student;
        } elseif ($company) {
            $criteria['company'] = $company;
        } else {
            return new JsonResponse(['error' => 'Utilisateur inconnu'], 400);
        }

        $matches = $em->getRepository(MatchEntity::class)->findBy($criteria, ['matchedAt' => 'DESC']);

        $data = array_map(function (MatchEntity $match) {
            return [
                'id' => $match->getId(),
                'student' => [
                    'id' => $match->getStudent()->getId(),
                    'name' => $match->getStudent()->getName()
                ],
                'company' => [
                    'id' => $match->getCompany()->getId(),
                    'name' => $match->getCompany()->getName()
                ],
                'matchedAt' => $match->getMatchedAt()->format('Y-m-d H:i:s'),
                'isContacted' => $match->getIsContacted(),
            ];
        }, $matches);

        return new JsonResponse($data);
    }
}
