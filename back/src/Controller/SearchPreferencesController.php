<?php

namespace App\Controller;

use App\Service\SearchPreferencesService;
use App\Service\TokenService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class SearchPreferencesController extends AbstractController
{
    #[Route('/student/manage-preferences', name: 'app_manage_preferences', methods: ['POST'])]
    public function managePreferences(
        Request $request,
        SearchPreferencesService $service,
        TokenService $tokenService
    ): JsonResponse 
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès réservé aux étudiants'], 403);
            }

            $data = json_decode($request->getContent(), true);
            $preferences = $service->managePreferences($user, $data);

            if (is_array($preferences) && isset($preferences['error'])) {
                return new JsonResponse($preferences, 400);
            }

            return new JsonResponse($service->modelJson($preferences), 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/preferences/{id}', name: 'app_get_preferences', methods: ['GET'])]
    public function getPreferences(
        int $id,
        Request $request,
        SearchPreferencesService $service,
        TokenService $tokenService
    ): JsonResponse 
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }

            $result = $service->getPreferences($user, $id);
            $status = isset($result['error']) ? 400 : 200;

            return new JsonResponse($result, $status);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/preferences', name: 'app_get_all_preferences', methods: ['GET'])]
    public function getAllPreferences(
        Request $request,
        SearchPreferencesService $service,
        TokenService $tokenService
    ): JsonResponse 
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }

            $result = $service->getAllPreferences($user);
            $status = isset($result['error']) ? 400 : 200;

            return new JsonResponse($result, $status);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/delete-preferences/{id}', name: 'app_delete_preferences', methods: ['DELETE'])]
    public function deletePreferences(
        int $id,
        Request $request,
        SearchPreferencesService $service,
        TokenService $tokenService
    ): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }

            $result = $service->deletePreferences($user, $id);
            $status = isset($result['error']) ? 400 : 200;

            return new JsonResponse($result, $status);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
