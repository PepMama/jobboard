<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\StudentService;

final class StudentController extends AbstractController
{
    #[Route('/student/complete-profile', name: 'app_complete_profile', methods: ['POST'])]
    public function completeProfile(): JsonResponse
    {

    }
}
