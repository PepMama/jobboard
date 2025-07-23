<?php

namespace App\Controller;

use App\Service\TokenService;
use App\Service\CompanyService;
use App\Service\StudentService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImageController extends AbstractController
{
    #[Route('/company/upload-logo', name: 'app_upload_company_logo', methods: ['POST'])]
    public function uploadCompanyLogo(
        Request $request,
        TokenService $tokenService,
        CompanyService $companyService
    ): JsonResponse {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'company') {
                return new JsonResponse(['error' => 'Accès réservé aux entreprises'], 403);
            }

            $uploadedFile = $request->files->get('logo');
            if (!$uploadedFile) {
                return new JsonResponse(['error' => 'Aucun fichier fourni'], 400);
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($uploadedFile->getMimeType(), $allowedTypes)) {
                return new JsonResponse(['error' => 'Type de fichier non autorisé. Utilisez JPG, PNG, GIF ou WebP'], 400);
            }

            if ($uploadedFile->getSize() > 5 * 1024 * 1024) {
                return new JsonResponse(['error' => 'Fichier trop volumineux. Maximum 5MB'], 400);
            }

            $company = $companyService->getCompanyByUser($user);

            if (!$company) {
                return new JsonResponse(['error' => 'Entreprise non trouvée'], 404);
            }

            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/logos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if ($company->getLogo()) {
                $oldLogoPath = $this->getParameter('kernel.project_dir') . '/public' . $company->getLogo();
                if (file_exists($oldLogoPath)) {
                    unlink($oldLogoPath);
                }
            }

            $extension = $uploadedFile->getClientOriginalExtension();
            $filename = 'logo_' . uniqid() . '.' . $extension;

            $fullFilePath = $uploadDir . $filename;
            while (file_exists($fullFilePath)) {
                $filename = 'logo_' . uniqid() . '.' . $extension;
                $fullFilePath = $uploadDir . $filename;
            }

            $uploadedFile->move($uploadDir, $filename);
            $filePath = '/uploads/logos/' . $filename;
            $baseUrl = $this->getParameter('app.url');
            $fullUrl = $baseUrl . $filePath;

            if (!file_exists($fullFilePath)) {
                throw new \Exception('Erreur lors de la création du fichier');
            }

            $company->setLogo($filePath);
            $companyService->completeProfileCompanyService($user, [
                'logo' => $filePath,
                'name' => $company->getName(),
                'phone_number' => $company->getPhoneNumber(),
                'website' => $company->getWebsite(),
                'linkedin' => $company->getLinkedin(),
                'industry' => $company->getIndustry(),
                'city' => $company->getCity(),
                'address' => $company->getAddress(),
                'postal_code' => $company->getPostalCode(),
                'description' => $company->getDescription(),
            ]);

            return new JsonResponse([
                'message' => 'Logo mis à jour avec succès',
                'logo_url' => $fullUrl
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/upload-photo', name: 'app_upload_student_photo', methods: ['POST'])]
    public function uploadStudentPhoto(
        Request $request,
        TokenService $tokenService,
        StudentService $studentService
    ): JsonResponse {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès réservé aux étudiants'], 403);
            }

            $uploadedFile = $request->files->get('photo');
            if (!$uploadedFile) {
                return new JsonResponse(['error' => 'Aucun fichier fourni'], 400);
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($uploadedFile->getMimeType(), $allowedTypes)) {
                return new JsonResponse(['error' => 'Type de fichier non autorisé. Utilisez JPG, PNG, GIF ou WebP'], 400);
            }

            if ($uploadedFile->getSize() > 5 * 1024 * 1024) {
                return new JsonResponse(['error' => 'Fichier trop volumineux. Maximum 5MB'], 400);
            }

            $student = $studentService->getStudentByUser($user);

            if (!$student) {
                return new JsonResponse(['error' => 'Étudiant non trouvé'], 404);
            }

            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/photos/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Supprimer l'ancienne photo s'elle existe
            if ($student->getPhoto()) {
                $oldPhotoPath = $this->getParameter('kernel.project_dir') . '/public' . $student->getPhoto();
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }

            $extension = $uploadedFile->getClientOriginalExtension();
            $filename = 'photo_' . uniqid() . '.' . $extension;
            
            $fullFilePath = $uploadDir . $filename;
            while (file_exists($fullFilePath)) {
                $filename = 'photo_' . uniqid() . '.' . $extension;
                $fullFilePath = $uploadDir . $filename;
            }

            $uploadedFile->move($uploadDir, $filename);
            $filePath = '/uploads/photos/' . $filename;
            $baseUrl = $this->getParameter('app.url');
            $fullUrl = $baseUrl . $filePath;

            if (!file_exists($fullFilePath)) {
                throw new \Exception('Erreur lors de la création du fichier');
            }
            
            $student->setPhoto($filePath);
            $studentService->completeProfileService($user, [
                'photo' => $filePath,
                'name' => $student->getName(),
                'firstname' => $student->getFirstname(),
                'phone_number' => $student->getPhoneNumber(),
                'age' => $student->getAge(),
                'city' => $student->getCity(),
                'address' => $student->getAddress(),
                'postal_code' => $student->getPostalCode(),
                'description' => $student->getDescription(),
                'linkedin' => $student->getLinkedin(),
                'github' => $student->getGithub(),
                'cv' => $student->getCv(),
            ]);
            return new JsonResponse([
                'message' => 'Photo mise à jour avec succès',
                'photo_url' => $fullUrl
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
} 