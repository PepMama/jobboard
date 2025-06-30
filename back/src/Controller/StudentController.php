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

            return new JsonResponse([
                'firstname'     => $student->getFirstname(),
                'name'          => $student->getName(),
                'phone_number'  => $student->getPhoneNumber(),
                'age'           => $student->getAge(),
                'address'       => $student->getAddress(),
                'city'          => $student->getCity(),
                'postal_code'   => $student->getPostalCode(),
                'description'   => $student->getDescription(),
                'photo'         => $student->getPhoto(),
                'linkedin'      => $student->getLinkedin(),
                'github'        => $student->getGithub(),
                'cv'            => $student->getCv(),
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
            'id'        => $s->getId(),
            'firstname' => $s->getFirstname(),
            'name'      => $s->getName(),
            'city'      => $s->getCity(),
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



}
