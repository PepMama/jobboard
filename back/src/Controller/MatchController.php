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
            $company = 
            $job->getCompany();

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
                    'email' => $company->getUser() ? $company->getUser()->getEmail() : null,
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
                    'email' => $student->getUser() ? $student->getUser()->getEmail() : null,
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

    #[Route('/{id}', name: 'app_delete_match', methods: ['DELETE'])]
    public function deleteMatch(
        int $id,
        Request $request,
        TokenService $tokenService,
        EntityManagerInterface $em
    ): JsonResponse {
        $user = $tokenService->getUserFromRequest($request);
        $match = $em->getRepository(MatchEntity::class)->find($id);
        
        if (!$match) {
            return new JsonResponse(['error' => 'Match non trouvé'], 404);
        }

        // Vérifie que l'utilisateur est bien concerné par le match
        $isStudent = $match->getStudent() && $match->getStudent()->getUser() && $match->getStudent()->getUser()->getId() === $user->getId();
        $isCompany = $match->getCompany() && $match->getCompany()->getUser() && $match->getCompany()->getUser()->getId() === $user->getId();
        if (!$isStudent && !$isCompany) {
            return new JsonResponse(['error' => 'Accès interdit'], 403);
        }

        // Supprimer les likes associés côté étudiant
        $likesStudentRepo = $em->getRepository(\App\Entity\LikesStudent::class);
        $likesOfferRepo = $em->getRepository(\App\Entity\LikesOffer::class);
        $likesStudent = $likesStudentRepo->findBy([
            'student' => $match->getStudent(),
            'company' => $match->getCompany(),
            'jobOffer' => $match->getJob()
        ]);
        foreach ($likesStudent as $like) {
            $em->remove($like);
        }

        // Supprimer les likes associés côté entreprise
        $likesOffer = $likesOfferRepo->findBy([
            'student' => $match->getStudent(),
            'jobOffer' => $match->getJob()
        ]);

        foreach ($likesOffer as $like) {
            $em->remove($like);
        }

        $likesOfferCompany = $likesOfferRepo->findBy([
            'jobOffer' => $match->getJob(),
            'student' => $match->getStudent()
        ]);

        foreach ($likesOfferCompany as $like) {
            $em->remove($like);
        }

        $likesStudentCompany = $likesStudentRepo->findBy([
            'company' => $match->getCompany(),
            'jobOffer' => $match->getJob()
        ]);

        foreach ($likesStudentCompany as $like) {
            $em->remove($like);
        }

        $em->remove($match);
        $em->flush();

        return new JsonResponse(['message' => 'Match supprimé']);
    }
}

