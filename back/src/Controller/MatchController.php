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

    #[Route('/student', name: 'app_student_matches', methods: ['GET'])]
    public function getStudentMatches(
        Request $request,
        TokenService $tokenService,
        StudentService $studentService,
        EntityManagerInterface $em
    ): JsonResponse {
        $user = $tokenService->getUserFromRequest($request);
        $student = $studentService->getStudentByUser($user);

        if (!$student) {
            return new JsonResponse(['error' => 'Étudiant non trouvé'], 404);
        }

        $matches = $em->getRepository(MatchEntity::class)->findBy(
            ['student' => $student, 'isValid' => true],
            ['matchedAt' => 'DESC']
        );

        $data = array_map(function (MatchEntity $match) {
            $company = $match->getCompany();
            return [
                'matchId' => $match->getId(),
                'company' => [
                    'id' => $company->getId(),
                    'name' => $company->getName(),
                    'industry' => $company->getIndustry(),
                    'city' => $company->getCity(),
                    'logo' => $company->getLogo(),
                ],
                'matchedAt' => $match->getMatchedAt()->format('Y-m-d H:i:s'),
                'isContacted' => $match->getIsContacted(),
            ];
        }, $matches);

        return new JsonResponse($data);
    }

    #[Route('/company', name: 'app_company_matches', methods: ['GET'])]
    public function getCompanyMatches(
        Request $request,
        TokenService $tokenService,
        CompanyService $companyService,
        EntityManagerInterface $em
    ): JsonResponse {
        $user = $tokenService->getUserFromRequest($request);
        $company = $companyService->getCompanyByUser($user);

        if (!$company) {
            return new JsonResponse(['error' => 'Entreprise non trouvée'], 404);
        }
        $matches = $em->getRepository(MatchEntity::class)->findBy(
            ['company' => $company, 'isValid' => true],
            ['matchedAt' => 'DESC']
        );
        $data = array_map(function (MatchEntity $match) {
            $student = $match->getStudent();
            return [
                'matchId' => $match->getId(),
                'student' => [
                    'id' => $student->getId(),
                    'name' => $student->getFirstname() . ' ' . $student->getName(),
                    'city' => $student->getCity(),
                    'photo' => $student->getPhoto(),
                ],
                'matchedAt' => $match->getMatchedAt()->format('Y-m-d H:i:s'),
                'isContacted' => $match->getIsContacted(),
            ];
        }, $matches);
        return new JsonResponse($data);
    }
}

