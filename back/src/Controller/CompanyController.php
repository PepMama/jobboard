<?php

namespace App\Controller;

use App\Service\CompanyService;
use App\Service\TokenService;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CompanyController extends AbstractController
{
    #[Route('/company/profile', name: 'app_complete_profile_company', methods: ['PUT'])]
    public function completeProfile(Request $request, CompanyService $companyService, TokenService $tokenService): JsonResponse
    {
        try{
            $user = $tokenService->getUserFromRequest($request);
            if($user->getRole() !== 'company'){
                return new JsonResponse(['error' => 'Accès réservé aux entreprises'], 403);
            }

            $data = json_decode($request->getContent(), true);
            $company = $companyService->completeProfileCompanyService($user, $data);

            return new JsonResponse([
                'message' => 'Profil entreprise mis à jour',
                'id_company' => $company->getId()
            ]);
        }
        catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 401);
        }
    }
}
