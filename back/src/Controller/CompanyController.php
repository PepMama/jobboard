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

    #[Route('/company/profile', name: 'app_get_company_profile', methods: ['GET'])]
    public function getCompanyProfile(Request $request, CompanyService $companyService, TokenService $tokenService): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);

            if ($user->getRole() !== 'company') {
                return new JsonResponse(['error' => 'Accès réservé aux entreprises'], 403);
            }

            $company = $companyService->getCompanyByUser($user);

            if (!$company) {
                return new JsonResponse(null, 204);
            }

            return new JsonResponse([
                'name' => $company->getName(),
                'phone_number' => $company->getPhoneNumber(),
                'city' => $company->getCity(),
                'address' => $company->getAddress(),
                'postal_code' => $company->getPostalCode(),
                'linkedin' => $company->getLinkedin(),
                'website' => $company->getWebsite(),
                'description' => $company->getDescription(),
                'industry' => $company->getIndustry()
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 401);
        }
    }

    #[Route('/company/{id<\d+>}', name: 'public_company_profile', methods: ['GET'])]
    public function publicCompanyProfile(string $name, CompanyService $companyService): JsonResponse
    {
        $company = $companyService->getCompanyByName($name);

        if (!$company) {
            return new JsonResponse(null, 204);
        }

        return new JsonResponse([
            'name' => $company->getName(),
            'phone_number' => $company->getPhoneNumber(),
            'city' => $company->getCity(),
            'address' => $company->getAddress(),
            'postal_code' => $company->getPostalCode(),
            'website' => $company->getWebsite(),
            'linkedin' => $company->getLinkedin(),
            'description' => $company->getDescription(),
            'industry' => $company->getIndustry()
        ]);
    }
}
