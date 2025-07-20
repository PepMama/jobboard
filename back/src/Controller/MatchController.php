<?php

namespace App\Controller;

use App\Entity\MatchEntity;
use App\Service\StudentService;
use App\Service\CompanyService;
use App\Service\TokenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\JobOffer;

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
        if ($student) {
            $count = $em->getRepository(MatchEntity::class)->count([
                'student' => $student,
                'isValid' => true
            ]);
            return new JsonResponse(['matchCount' => $count]);
        }
        $company = $companyService->getCompanyByUser($user);
        if ($company) {
            if (!$company) {
                return new JsonResponse(['error' => 'Entreprise non trouvée'], 404);
            }

            $count = $em->getRepository(MatchEntity::class)->count([
                'company' => $company,
                'isValid' => true
            ]);
            return new JsonResponse(['matchCount' => $count]);
        }
        return new JsonResponse(['error' => 'Utilisateur inconnu'], 400);
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
            $job = $match->getJob();
            $company = $job->getCompany();

            return [
                'matchId' => $match->getId(),
                'job' => [
                    'id' => $job->getId(),
                    'title' => $job->getTitle(),
                    'city' => $job->getCity(),
                    'contractType' => $job->getContractType(),
                    'remote' => $job->getRemote(),
                    'salary' => $job->getSalary(),
                    'description' => $job->getDescription(),
                ],
                'company' => [
                    'id' => $company->getId(),
                    'name' => $company->getName(),
                    'industry' => $company->getIndustry(),
                    'city' => $company->getCity(),
                    'logo' => $company->getLogo(),
                    'linkedin' => $company->getLinkedin(),
                    'website' => $company->getWebsite(),
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
        $jobOffers = $em->getRepository(JobOffer::class)->findBy(['company' => $company]);
        if (empty($jobOffers)) {
            return new JsonResponse([], 200);
        }
        $matches = $em->getRepository(MatchEntity::class)
            ->createQueryBuilder('m')
            ->innerJoin('m.job', 'j')
            ->where('m.isValid = true')
            ->andWhere('j IN (:jobs)')
            ->setParameter('jobs', $jobOffers)
            ->orderBy('m.matchedAt', 'DESC')
            ->getQuery()
            ->getResult();

        $data = array_map(function (MatchEntity $match) {
            $student = $match->getStudent();
            $job = $match->getJob();
            return [
                'matchId' => $match->getId(),
                'student' => [
                    'id' => $student->getId(),
                    'name' => $student->getFirstname() . ' ' . $student->getName(),
                    'city' => $student->getCity(),
                    'photo' => $student->getPhoto(),
                    'description' => $student->getDescription(),
                    'linkedin' => $student->getLinkedin(),
                    'github' => $student->getGithub(),
                    'cv' => $student->getCv(),
                ],
                'job' => [
                    'id' => $job->getId(),
                    'title' => $job->getTitle(),
                    'city' => $job->getCity(),
                    'contractType' => $job->getContractType(),
                    'remote' => $job->getRemote(),
                    'salary' => $job->getSalary(),
                ],
                'matchedAt' => $match->getMatchedAt()->format('Y-m-d H:i:s'),
                'isContacted' => $match->getIsContacted(),
            ];
        }, $matches);
        return new JsonResponse($data);
    }

}

