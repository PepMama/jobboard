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
use App\Entity\MatchEntity;
use App\Entity\Notification;
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

            $company = $offer->getCompany();

            $existingCompanyLike = $em->getRepository(LikesStudent::class)->findOneBy([
                'student' => $student,
                'company' => $company
            ]);

            if ($existingCompanyLike) {
                $existingMatch = $em->getRepository(MatchEntity::class)->findOneBy([
                    'student' => $student,
                    'company' => $company
                ]);

                if (!$existingMatch) {
                    $match = new MatchEntity();
                    $match->setStudent($student);
                    $match->setCompany($company);
                    $match->setMatchedAt(new \DateTime());
                    $match->setJob($offer);
                    $match->setIsValid(true);
                    $match->setIsContacted(false);
                    $em->persist($match);
                }
            }

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

            $studentLikesOffer = $em->getRepository(LikesOffer::class)->findOneBy([
                'student' => $student,
            ]);

            if ($studentLikesOffer) {
                $jobOffer = $studentLikesOffer->getJobOffer();
                if ($jobOffer && $jobOffer->getCompany()->getId() === $company->getId()) {
                    $existingMatch = $em->getRepository(MatchEntity::class)->findOneBy([
                        'student' => $student,
                        'company' => $company
                    ]);

                    if (!$existingMatch) {
                        $match = new MatchEntity();
                        $match->setStudent($student);
                        $match->setCompany($company);
                        $match->setJob($jobOffer);
                        $match->setMatchedAt(new \DateTime());
                        $match->setIsValid(true);
                        $match->setIsContacted(true);
                        $em->persist($match);
                    }
                }
            }

            $em->flush();
            return new JsonResponse(['message' => 'Étudiant liké']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/company/liked-students', name: 'app_company_liked_students', methods: ['GET'])]
    public function getLikedStudents(
        Request $request,
        TokenService $tokenService,
        CompanyService $companyService,
        EntityManagerInterface $em
    ): JsonResponse {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'company') {
                return new JsonResponse(['error' => 'Accès réservé aux entreprises'], 403);
            }

            $company = $companyService->getCompanyByUser($user);
            if (!$company) {
                return new JsonResponse(['error' => 'Entreprise non trouvée'], 404);
            }

            $likes = $em->getRepository(LikesStudent::class)->findBy(['company' => $company]);
            $likedStudents = [];
            foreach ($likes as $like) {
                $student = $like->getStudent();
                if ($student) {
                    $photoUrl = $student->getPhoto() ? 'http://localhost:8000' . $student->getPhoto() : null;

                    $likedStudents[] = [
                        'id' => $student->getId(),
                        'firstname' => $student->getFirstname(),
                        'name' => $student->getName(),
                        'city' => $student->getCity(),
                        'description' => $student->getDescription(),
                        'photo' => $photoUrl,
                        'linkedin' => $student->getLinkedin(),
                        'github' => $student->getGithub(),
                        'cv' => $student->getCv(),
                        'liked_at' => $like->getLikedAt()->format('Y-m-d H:i:s')
                    ];
                }
            }

            return new JsonResponse($likedStudents, 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/company/unlike-student/{id}', name: 'app_unlike_student', methods: ['DELETE'])]
    public function unlikeStudent(
        int $id,
        Request $request,
        TokenService $tokenService,
        CompanyService $companyService,
        EntityManagerInterface $em
    ): JsonResponse {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'company') {
                return new JsonResponse(['error' => 'Accès réservé aux entreprises'], 403);
            }
            $company = $companyService->getCompanyByUser($user);
            if (!$company) {
                return new JsonResponse(['error' => 'Entreprise non trouvée'], 404);
            }
            $student = $em->getRepository(Student::class)->find($id);
            if (!$student) {
                return new JsonResponse(['error' => 'Étudiant non trouvé'], 404);
            }

            // Faut voir si il y a un like
            $like = $em->getRepository(LikesStudent::class)->findOneBy([
                'company' => $company,
                'student' => $student
            ]);
            if (!$like) {
                return new JsonResponse(['error' => 'Like non trouvé'], 404);
            }
            $em->remove($like);

            $matches = $em->getRepository(MatchEntity::class)->findBy([
                'company' => $company,
                'student' => $student
            ]);
            foreach ($matches as $match) {
                $em->remove($match);
            }

            $em->flush();

            return new JsonResponse(['message' => 'tous les matchs supprimés']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
