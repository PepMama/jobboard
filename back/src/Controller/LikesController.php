<?php
namespace App\Controller;

use App\Entity\JobOffer;
use App\Entity\LikesOffer;
use App\Service\TokenService;
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
}
