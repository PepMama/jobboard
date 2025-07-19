<?php

namespace App\Controller;

use App\Service\TokenService;
use App\Service\StudentService;
use App\Service\JobOfferService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JobOfferController extends AbstractController
{
    #[Route('/company/manage-offer', name: 'app_manage_offer', methods: ['POST'])]
    public function manageOffer(
        Request $request,
        JobOfferService $service,
        TokenService $tokenService
    ): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'company') {
                return new JsonResponse(['error' => 'Accès réservé aux entreprises'], 403);
            }

            $data = json_decode($request->getContent(), true);
            $offer = $service->manageOffer($user, $data);

            if (is_array($offer) && isset($offer['error'])) {
                return new JsonResponse($offer, 400);
            }

            return new JsonResponse($service->modelJson($offer), 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/company/offers', name: 'app_get_all_offers', methods: ['GET'])]
    public function getAllOffers(
        Request $request,
        JobOfferService $service,
        TokenService $tokenService
    ): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if (!$user) {
                return new JsonResponse(['error' => 'Unauthorized'], 401);
            }
            
            if ($user->getRole() !== 'company') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }
    
            $result = $service->getAllOffers($user);
            if (isset($result['error'])) {
                return new JsonResponse($result, 400);
            }
    
            return new JsonResponse($result, 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    

    #[Route('/company/offer/{id}', name: 'app_get_offer', methods: ['GET'])]
    public function getOffer(
        int $id,
        Request $request,
        JobOfferService $service,
        TokenService $tokenService
    ): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'company') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }

            $result = $service->getOffer($user, $id);
            $status = isset($result['error']) ? 400 : 200;

            return new JsonResponse($result, $status);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/company/delete-offer/{id}', name: 'app_delete_offer', methods: ['DELETE'])]
    public function deleteOffer(
        int $id,
        Request $request,
        JobOfferService $service,
        TokenService $tokenService
    ): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'company') {
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }

            $result = $service->deleteOffer($user, $id);
            $status = isset($result['error']) ? 400 : 200;

            return new JsonResponse($result, $status);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/offers', name: 'app_student_offers', methods: ['GET'])]
    public function getOffersForStudents(
        Request $request,
        JobOfferService $service,
        TokenService $tokenService,
        StudentService $studentService
    ): JsonResponse {
        try {
            $keyword = $request->query->get('keyword'); 
            $city = $request->query->get('city');

            $user = $tokenService->getUserFromRequest($request);
            $student = $studentService->getStudentByUser($user);

            $offers = $service->getOffersForStudents($keyword, $city, $student->getId());
            return new JsonResponse($offers, 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}

