<?php

namespace App\Controller;

use App\Service\TokenService;
use App\Service\EducationService;
use App\Entity\Student;
use App\Entity\User;
use App\Entity\Education;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class EducationController extends AbstractController
{
    #[Route('/student/manage-education', name: 'app_manage_education', methods: ['POST'])]
    public function manageEducation(
        Request $request,
        EducationService $educationService,
        TokenService $tokenService
    ): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès réservé aux étudiants'], 403);
            }

            $data = json_decode($request->getContent(), true);
            $education = $educationService->manageEducation($user, $data);

            if (is_array($education) && isset($education['error'])) {
                return new JsonResponse(['error' => $education['error']], 400);
            }

            return new JsonResponse(
                $educationService->modelJson($education),
                200
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/education/{id}', name: 'app_get_education', methods: ['GET'])]
    public function getEducation(
        int $id,
        Request $request,
        EducationService $educationService,
        TokenService $tokenService
    ): JsonResponse 
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }

            $result = $educationService->getEducation($user, $id);

            if (is_array($result) && isset($result['error'])) {
                return new JsonResponse($result, 400);
            }

            return new JsonResponse($result, 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/educations', name: 'app_get_all_educations', methods: ['GET'])]
    public function getAllEducations(
        Request $request,
        EducationService $educationService,
        TokenService $tokenService
    ): JsonResponse 
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }

            $result = $educationService->getAllEducations($user);

            if (is_array($result) && isset($result['error'])) {
                return new JsonResponse($result, 400);
            }

            return new JsonResponse($result, 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/delete-education/{id}', name: 'app_delete_education', methods: ['DELETE'])]
    public function deleteEducation(
        int $id,
        Request $request,
        EducationService $educationService,
        TokenService $tokenService
    ): JsonResponse 
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }

            $result = $educationService->deleteEducation($user, $id);

            $status = isset($result['error']) ? 400 : 200;
            return new JsonResponse($result, $status);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }



}