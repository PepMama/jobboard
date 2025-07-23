<?php

namespace App\Controller;

use App\Service\StudentService;
use App\Service\TokenService;
use App\Entity\Student;
use App\Entity\Users;
use App\Entity\Company;
use App\Entity\LikesStudent;
use App\Entity\LikesOffer;
use App\Entity\JobOffer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\MatchEntity;
use Doctrine\ORM\EntityManagerInterface;

class StudentController extends AbstractController
{
    #[Route('/student/manage-profile', name: 'app_complete_profile', methods: ['PUT'])]
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

    #[Route('/student/profile', name: 'app_get_student_profile', methods: ['GET'])]
    public function getStudentProfile(
        Request $request,
        StudentService $studentService,
        TokenService $tokenService
    ): JsonResponse {
        try {
            $user = $tokenService->getUserFromRequest($request);

            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès réservé aux étudiants'], 403);
            }

            $student = $studentService->getStudentByUser($user);

            if (!$student) {
                return new JsonResponse(null, 204);
            }

            $baseUrl = $this->getParameter('app.url');
            $photoUrl = $student->getPhoto() ? $baseUrl . $student->getPhoto() : null;
            $cvUrl = $student->getCv() ? $baseUrl . $student->getCv() : null;

            return new JsonResponse([
                'firstname' => $student->getFirstname(),
                'name' => $student->getName(),
                'phone_number' => $student->getPhoneNumber(),
                'age' => $student->getAge(),
                'address' => $student->getAddress(),
                'city' => $student->getCity(),
                'postal_code' => $student->getPostalCode(),
                'title' => $student->getTitle(),
                'description' => $student->getDescription(),
                'photo' => $photoUrl,
                'linkedin' => $student->getLinkedin(),
                'github' => $student->getGithub(),
                'cv' => $cvUrl,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 401);
        }
    }

    #[Route('/students', name: 'app_get_all_students', methods: ['GET'])]
    public function getAllStudents(StudentService $studentService): JsonResponse
    {
        $students = $studentService->getAllStudent();

        $data = array_map(fn(Student $s) => [
            'id' => $s->getId(),
            'firstname' => $s->getFirstname(),
            'name' => $s->getName(),
            'city' => $s->getCity(),
        ], $students);

        return new JsonResponse($data);
    }

    #[Route('/student/upload-cv', name: 'app_upload_cv', methods: ['POST'])]
    public function uploadCv(Request $request, TokenService $tokenService, StudentService $studentService): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            $student = $studentService->getStudentByUser($user);

            $file = $request->files->get('cv');
            if (!$file || $file->getClientOriginalExtension() !== 'pdf') {
                return new JsonResponse(['error' => 'Seuls les fichiers PDF sont autorisés'], 400);
            }


            if ($student->getCv()) {
                $oldPath = $this->getParameter('kernel.project_dir') . '/public' . $student->getCv();
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $fileName = uniqid() . '.pdf';
            $file->move($this->getParameter('kernel.project_dir') . '/public/uploads/cvs', $fileName);

            $student->setCv('/uploads/cvs/' . $fileName);
            $studentService->save($student);

            return new JsonResponse(['cv' => $student->getCv()]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/delete-cv', name: 'app_delete_cv', methods: ['DELETE'])]
    public function deleteCv(Request $request, TokenService $tokenService, StudentService $studentService): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            $student = $studentService->getStudentByUser($user);

            if ($student->getCv()) {
                $cvPath = $this->getParameter('kernel.project_dir') . '/public' . $student->getCv();
                if (file_exists($cvPath)) {
                    unlink($cvPath);
                }
                $student->setCv(null);
                $studentService->save($student);
            }

            return new JsonResponse(['message' => 'CV supprimé']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/update-github', name: 'app_update_github', methods: ['PUT'])]
    public function updateGithub(Request $request, TokenService $tokenService, StudentService $studentService): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            $student = $studentService->getStudentByUser($user);

            $data = json_decode($request->getContent(), true);
            $student->setGithub($data['github'] ?? null);
            $studentService->save($student);

            return new JsonResponse(['github' => $student->getGithub()]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/update-linkedin', name: 'app_update_linkedin', methods: ['PUT'])]
    public function updateLinkedin(Request $request, TokenService $tokenService, StudentService $studentService): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            $student = $studentService->getStudentByUser($user);

            $data = json_decode($request->getContent(), true);
            $student->setLinkedin($data['linkedin'] ?? null);
            $studentService->save($student);

            return new JsonResponse(['linkedin' => $student->getLinkedin()]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/liked-companies', name: 'app_get_liked_companies', methods: ['GET'])]
    public function getLikedCompanies(Request $request, TokenService $tokenService, \Doctrine\ORM\EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès réservé aux étudiants'], 403);
            }

            $student = $entityManager->getRepository(Student::class)->findOneBy(['user' => $user]);
            if (!$student) {
                return new JsonResponse(['error' => 'Étudiant non trouvé'], 404);
            }

            $likesRepository = $entityManager->getRepository(LikesStudent::class);
            $likes = $likesRepository->findBy(['student' => $student]);

            $companies = [];
            foreach ($likes as $like) {
                $company = $like->getCompany();
                $logoUrl = $company->getLogo() ? $this->getParameter('app.url') . $company->getLogo() : null;

                $companies[] = [
                    'id' => $company->getId(),
                    'name' => $company->getName(),
                    'city' => $company->getCity(),
                    'description' => $company->getDescription(),
                    'logo' => $logoUrl,
                    'website' => $company->getWebsite(),
                    'industry' => $company->getIndustry(),
                    'liked_at' => $like->getLikedAt()->format('Y-m-d H:i:s')
                ];
            }

            return new JsonResponse($companies);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/unlike-company/{companyId}', name: 'app_unlike_company', methods: ['DELETE'])]
    public function unlikeCompany(int $companyId, Request $request, TokenService $tokenService, \Doctrine\ORM\EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès réservé aux étudiants'], 403);
            }

            $student = $entityManager->getRepository(Student::class)->findOneBy(['user' => $user]);
            if (!$student) {
                return new JsonResponse(['error' => 'Étudiant non trouvé'], 404);
            }

            $company = $entityManager->getRepository(Company::class)->find($companyId);
            if (!$company) {
                return new JsonResponse(['error' => 'Entreprise non trouvée'], 404);
            }

            $like = $entityManager->getRepository(LikesStudent::class)->findOneBy([
                'student' => $student,
                'company' => $company
            ]);

            if (!$like) {
                return new JsonResponse(['error' => 'Like non trouvé'], 404);
            }

            $entityManager->remove($like);
            $entityManager->flush();

            return new JsonResponse(['message' => 'Like supprimé']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/liked-offers', name: 'app_get_liked_offers', methods: ['GET'])]
    public function getLikedOffers(Request $request, TokenService $tokenService, \Doctrine\ORM\EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès réservé aux étudiants'], 403);
            }
            $student = $entityManager->getRepository(Student::class)->findOneBy(['user' => $user]);
            if (!$student) {
                return new JsonResponse(['error' => 'Étudiant non trouvé'], 404);
            }

            $searchTitle = $request->query->get('title', '');
            $searchContractType = $request->query->get('contract_type', '');
            $searchCompany = $request->query->get('company', '');
            $searchCity = $request->query->get('city', '');

            $qb = $entityManager->createQueryBuilder();
            $qb->select('lo', 'jo', 'c')
               ->from(LikesOffer::class, 'lo')
               ->join('lo.jobOffer', 'jo')
               ->join('jo.company', 'c')
               ->where('lo.student = :student')
               ->setParameter('student', $student);

            if (!empty($searchTitle)) {
                $qb->andWhere('jo.title LIKE :title')
                   ->setParameter('title', '%' . $searchTitle . '%');
            }

            if (!empty($searchContractType)) {
                $qb->andWhere('jo.contractType = :contractType')
                   ->setParameter('contractType', $searchContractType);
            }

            if (!empty($searchCompany)) {
                $qb->andWhere('c.name LIKE :company')
                   ->setParameter('company', '%' . $searchCompany . '%');
            }

            if (!empty($searchCity)) {
                $qb->andWhere('jo.city LIKE :city')
                   ->setParameter('city', '%' . $searchCity . '%');
            }

            $likes = $qb->getQuery()->getResult();

            $offers = [];
            foreach ($likes as $like) {
                $offer = $like->getJobOffer();
                $company = $offer->getCompany();
                $logoUrl = $company && $company->getLogo() ? $this->getParameter('app.url') . $company->getLogo() : null;
                $offers[] = [
                    'id' => $offer->getId(),
                    'title' => $offer->getTitle(),
                    'description' => $offer->getDescription(),
                    'contract_type' => $offer->getContractType(),
                    'salary' => $offer->getSalary(),
                    'city' => $offer->getCity(),
                    'remote' => $offer->getRemote(),
                    'start_date' => $offer->getStartDate()?->format('Y-m-d'),
                    'company' => [
                        'id' => $company?->getId(),
                        'name' => $company?->getName(),
                        'logo' => $logoUrl,
                        'city' => $company?->getCity(),
                        'industry' => $company?->getIndustry(),
                        'website' => $company?->getWebsite(),
                    ],
                    'liked_at' => $like->getLikedAt()->format('Y-m-d H:i:s'),
                ];
            }
            return new JsonResponse($offers);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/student/unlike-offer/{offerId}', name: 'app_unlike_offer', methods: ['DELETE'])]
    public function unlikeOffer(
        int $offerId,
        Request $request,
        TokenService $tokenService,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        try {
            $user = $tokenService->getUserFromRequest($request);
            if ($user->getRole() !== 'student') {
                return new JsonResponse(['error' => 'Accès réservé aux étudiants'], 403);
            }

            $student = $entityManager->getRepository(Student::class)
                ->findOneBy(['user' => $user]);
            if (!$student) {
                return new JsonResponse(['error' => 'Étudiant non trouvé'], 404);
            }

            $offer = $entityManager->getRepository(JobOffer::class)->find($offerId);
            if (!$offer) {
                return new JsonResponse(['error' => 'Offre non trouvée'], 404);
            }

            $like = $entityManager->getRepository(LikesOffer::class)->findOneBy([
                'student' => $student,
                'jobOffer' => $offer
            ]);
            if (!$like) {
                return new JsonResponse(['error' => 'Like non trouvé'], 404);
            }
            $entityManager->remove($like);

            $matches = $entityManager->getRepository(MatchEntity::class)->findBy([
                'student' => $student
            ]);

            foreach ($matches as $match) {
                $entityManager->remove($match);
            }

            $entityManager->flush();

            return new JsonResponse(['message' => 'Like supprimé et tous les matchs liés à l\'étudiant ont été supprimés']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }


    #[Route('/student/by-name/{name}', name: 'public_student_profile', methods: ['GET'])]
    public function publicStudentProfile(string $name, StudentService $studentService): JsonResponse
    {
        $student = $studentService->getStudentByName($name);

        if (!$student) {
            return new JsonResponse(['error' => 'Étudiant non trouvé'], 404);
        }
        $photoUrl = $student->getPhoto() ? $this->getParameter('app.url') . $student->getPhoto() : null;

        return new JsonResponse([
            'id' => $student->getId(),
            'firstname' => $student->getFirstname(),
            'name' => $student->getName(),
            'city' => $student->getCity(),
            'description' => $student->getDescription(),
            'linkedin' => $student->getLinkedin(),
            'github' => $student->getGithub(),
            'cv' => $student->getCv(),
            'phoneNumber' => $student->getPhoneNumber(),
            'address' => $student->getAddress(),
            'photo' => $student->getPhoto(),
            'postalCode' => $student->getPostalCode(),
        ]);
    }
}
