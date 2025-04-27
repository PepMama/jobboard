<?php

namespace App\Controller;

use App\Service\StudentService;
use App\Service\TokenService;
use App\Entity\Student;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

class StudentController extends AbstractController
{
    #[Route('/student/profile', name: 'app_complete_profile', methods: ['PUT'])]
    public function completeProfile(Request $request, StudentService $studentService, TokenService $tokenService): JsonResponse
{
    try {
        $user = $tokenService->getUserFromRequest($request);
        if ($user->getRole() !== 'student') {
            return new JsonResponse(['error' => 'Accès réservé aux étudiants'], 403);
        }

        $data = json_decode($request->getContent(), true);
        $student = $studentService->completeProfileService($user, $data);

        return new JsonResponse([
            'message' => 'Profil étudiant mis à jour',
            'id_student' => $student->getId()
        ]);
    } catch (\Exception $e) {
        return new JsonResponse(['error' => $e->getMessage()], 401);
    }
}
}
