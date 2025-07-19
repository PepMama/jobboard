<?php

namespace App\Controller;

use App\Entity\Users;
use App\Service\TokenService;
use App\Service\CompanyService;
use App\Service\StudentService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;


class CompanyController extends AbstractController
{
    #[Route('/company/profile', name: 'app_complete_profile_company', methods: ['PUT'])]
    public function completeProfile(Request $request, CompanyService $companyService, TokenService $tokenService): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'company') {
                return new JsonResponse(['error' => 'Accès réservé aux entreprises'], 403);
            }

            $data = json_decode($request->getContent(), true);
            $company = $companyService->completeProfileCompanyService($user, $data);

            return new JsonResponse([
                'message' => 'Profil entreprise mis à jour',
                'id_company' => $company->getId()
            ]);
        } catch (\Exception $e) {
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

            $logoUrl = $company->getLogo() ? 'http://localhost:8000' . $company->getLogo() : null;

            return new JsonResponse([
                'name' => $company->getName(),
                'phone_number' => $company->getPhoneNumber(),
                'city' => $company->getCity(),
                'address' => $company->getAddress(),
                'postal_code' => $company->getPostalCode(),
                'linkedin' => $company->getLinkedin(),
                'website' => $company->getWebsite(),
                'description' => $company->getDescription(),
                'industry' => $company->getIndustry(),
                'logo' => $logoUrl
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

        $logoUrl = $company->getLogo() ? 'http://localhost:8000' . $company->getLogo() : null;

        return new JsonResponse([
            'name' => $company->getName(),
            'phone_number' => $company->getPhoneNumber(),
            'city' => $company->getCity(),
            'address' => $company->getAddress(),
            'postal_code' => $company->getPostalCode(),
            'website' => $company->getWebsite(),
            'linkedin' => $company->getLinkedin(),
            'description' => $company->getDescription(),
            'industry' => $company->getIndustry(),
            'logo' => $logoUrl
        ]);
    }

    #[Route('/company/students', name: 'app_company_students_list', methods: ['GET'])]
    public function getStudentsForCompany(
        Request $request,
        StudentService $studentService,
        CompanyService $companyService,
        TokenService $tokenService,
    ): JsonResponse {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if (!$user) {
                return new JsonResponse(['error' => 'Unauthorized'], 401);
            }

            $company = $companyService->getCompanyByUser($user);
            if (!$company) {
                return new JsonResponse(['error' => 'Company not found'], 404);
            }

            $keyword = $request->query->get('keyword');
            $city = $request->query->get('city');
            $degree = $request->query->get('degree');
            $fieldOfStudy = $request->query->get('fieldOfStudy');
            $students = $studentService->getStudentsForCompany($keyword, $city, $degree, $fieldOfStudy, $company->getId());

            if (empty($students)) {
                return new JsonResponse([], 200);
            }

            return $this->json($students);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/company/by-name/{name}', name: 'app_company_by_name', methods: ['GET'])]
    public function getStudentsForCompanyByName(
        Request $request,
        string $name,
        CompanyService $companyService,
        TokenService $tokenService
    ): JsonResponse {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if (!$user) {
                return new JsonResponse(['error' => 'Unauthorized'], 401);
            }

            $company = $companyService->getCompanyByName($name);
            if (!$company) {
                return new JsonResponse(['error' => 'Company not found'], 404);
            }

            return $this->json(
                $company,
                200,
                [],
                [
                    AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($obj) {
                        return $obj->getId();
                    }
                ]
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
