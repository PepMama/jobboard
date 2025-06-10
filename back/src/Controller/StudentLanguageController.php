<?php

namespace App\Controller;

use App\Repository\LanguageRepository;
use App\Service\StudentLanguageService;
use App\Service\TokenService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class StudentLanguageController extends AbstractController
{
    #[Route('/student/add-language', name: 'app_add_student_language', methods: ['POST'])]
    public function addLanguage(Request $request, StudentLanguageService $service, TokenService $tokenService): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }

            $data = json_decode($request->getContent(), true);
            $result = $service->addLanguage($user, $data);

            if (is_array($result) && isset($result['error'])) {
                return new JsonResponse($result, 400);
            }

            return new JsonResponse(['message' => 'Langue ajoutée'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/languages', name: 'app_get_student_languages', methods: ['GET'])]
    public function getLanguages(Request $request, StudentLanguageService $service, TokenService $tokenService): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }

            return new JsonResponse($service->getLanguages($user), 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/delete-language/{id}', name: 'app_delete_student_language', methods: ['DELETE'])]
    public function deleteLanguage(int $id, Request $request, StudentLanguageService $service, TokenService $tokenService): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }

            $result = $service->deleteLanguage($user, $id);
            $status = isset($result['error']) ? 400 : 200;

            return new JsonResponse($result, $status);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/languages', name: 'app_get_languages', methods: ['GET'])]
    public function getAllLanguages(LanguageRepository $repo): JsonResponse
    {
        $languages = $repo->findAll();

        return new JsonResponse(array_map(fn($l) => [
            'id' => $l->getId(),
            'name' => $l->getName()
        ], $languages));
    }
}
