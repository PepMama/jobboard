<?php
namespace App\Controller;

use App\Entity\Student;
use App\Entity\JobOffer;
use App\Entity\LikesOffer;
use App\Entity\LikesStudent;
use App\Service\TokenService;
use App\Service\CompanyService;
use App\Service\StudentService;
use App\Repository\LikesOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LikesController extends AbstractController
{
    #[Route('/student/like-offer/{id}', name: 'app_like_offer', methods: ['POST'])]
    public function likeOffer(
        int $id,
        Request $request,
        TokenService $tokenService,
        StudentService $studentService,
        EntityManagerInterface $em
    ): JsonResponse {
        try {
            $user = $tokenService->getUserFromRequest($request);
            $student = $studentService->getStudentByUser($user);

            $offer = $em->getRepository(JobOffer::class)->find($id);
            if (!$offer) {
                return new JsonResponse(['error' => 'Offre non trouvée'], 404);
            }

            $like = new LikesOffer();
            $like->setStudent($student);
            $like->setJobOffer($offer);
            $em->persist($like);
            $em->flush();

            return new JsonResponse(['message' => 'Offre likée']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/company/contact-student/{id}', name: 'app_contact_student', methods: ['POST'])]
    public function contactStudent(
        int $id,
        Request $request,
        TokenService $tokenService,
        CompanyService $companyService,
        EntityManagerInterface $em
    ): JsonResponse {
        try {
            $user = $tokenService->getUserFromRequest($request);
            $company = $companyService->getCompanyByUser($user);

            $student = $em->getRepository(Student::class)->find($id);
            if (!$student) {
                return new JsonResponse(['error' => 'Étudiant non trouvé'], 404);
            }

            $like = new LikesStudent();
            $like->setCompany($company);
            $like->setStudent($student);
            $em->persist($like);
            $em->flush();

            return new JsonResponse(['message' => 'Étudiant liké']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
