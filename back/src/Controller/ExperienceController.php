<?php 

namespace App\Controller;

use App\Service\TokenService;
use App\Service\ExperienceService;
use App\Entity\Student;
use App\Entity\Users;
use App\Entity\Experience;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExperienceController extends AbstractController
{
    public function modelJson(array $experiences): array
    {
        return array_map([$this, 'formatDataJson'], $experiences);
    }

    #[Route('/student/manage-experience', name: 'app_manage_experience', methods: ['POST'])]
    public function manageExperience(Request $request, ExperienceService $experienceService, TokenService $tokenService): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if($user->getRole() !== 'student'){
                return new JsonResponse(['error' => 'Accès réservé aux étudiants'], 403);
            }

            $data = json_decode($request->getContent(), true);
            $experience = $experienceService->manageExperience($user, $data);

            if (is_array($experience) && isset($experience['error'])) {
                return new JsonResponse(['error' => $experience['error']], 400);
            }

            return $this->json(
                $experienceService->formatDataJson($experience),
                200
            );
        }catch (\Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/experience/{id}', name: 'app_get_experience', methods: ['GET'])]
    public function getExperience(int $id, Request $request, ExperienceService $experienceService, TokenService $tokenService): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);

            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }

            $result = $experienceService->getExperience($user, $id);

            // si le tableau est vide : on renvoie une erreur 400 sinon 200
            if (is_array($result) && isset($result['error'])) {
                return new JsonResponse($result, 400);
            }

            return new JsonResponse($result, 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/experiences', name: 'app_get_all_experiences', methods: ['GET'])]
    public function getAllExperiences(Request $request, ExperienceService $experienceService, TokenService $tokenService): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }

            $result = $experienceService->getAllExperiences($user);

            if (is_array($result) && isset($result['error'])) {
                return new JsonResponse($result, 400);
            }

            return new JsonResponse($result, 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }


    #[Route('/student/delete-experience/{id}', name: 'app_delete_experience', methods: ['DELETE'])]
    public function deleteExperience(int $id, Request $request, ExperienceService $experienceService, TokenService $tokenService): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);

            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }

            $result = $experienceService->deleteExperience($user, $id);

            $status = isset($result['error']) ? 400 : 200;
            return new JsonResponse($result, $status);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

}