<?php

namespace App\Controller;

use App\Repository\CompetencyRepository;
use App\Service\StudentCompetencyService;
use App\Service\TokenService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class StudentCompetencyController extends AbstractController
{
    #[Route('/competencies', name: 'app_get_competencies', methods: ['GET'])]
    public function getAllCompetencies(CompetencyRepository $repo): JsonResponse
    {
        $competencies = $repo->findAll();
        return new JsonResponse(array_map(fn($c) => [
            'id' => $c->getId(),
            'name' => $c->getName()
        ], $competencies));
    }

    #[Route('/student/competencies', name: 'app_get_student_competencies', methods: ['GET'])]
    public function getStudentCompetencies(Request $request, StudentCompetencyService $service, TokenService $tokenService): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }
            return new JsonResponse($service->getCompetencies($user), 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/add-competency', name: 'app_add_student_competency', methods: ['POST'])]
    public function addCompetency(Request $request, StudentCompetencyService $service, TokenService $tokenService): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }
            $data = json_decode($request->getContent(), true);
            $result = $service->addCompetency($user, $data);
            if (is_array($result) && isset($result['error'])) {
                return new JsonResponse($result, 400);
            }
            return new JsonResponse(['message' => 'Compétence ajoutée'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/delete-competency/{id}', name: 'app_delete_student_competency', methods: ['DELETE'])]
    public function deleteCompetency(int $id, Request $request, StudentCompetencyService $service, TokenService $tokenService): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }
            $result = $service->deleteCompetency($user, $id);
            $status = isset($result['error']) ? 400 : 200;
            return new JsonResponse($result, $status);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
} 