<?php

namespace App\Controller;

use App\Entity\Users;
use Firebase\JWT\JWT;
use App\Entity\Company;
use App\Entity\Student;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Vérifier si l'email existe déjà
            $existingUser = $em->getRepository(Users::class)->findOneBy(['email' => $data['email']]);
            if ($existingUser) {
                return new JsonResponse([
                    'error' => 'Cet utilisateur existe déjà.'
                ], 409);
            }

            // Ajouter l'user dans la base de données
            $user = new Users();
            $user->setEmail($data['email']);
            $user->setRole($data['role']);
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
    
            $em->persist($user);
            $em->flush();
    
            // Créer le token JWT
            $jwtKey = $this->getParameter('jwt_key');
            $payload = [
                'user_id' => $user->getId(),
                'role' => $user->getRoles()[1] ?? 'ROLE_USER',
                'exp' => time() + 3600,
            ];
    
            $token = JWT::encode($payload, $jwtKey, 'HS256');
    
            // Rediriger sur le bon tableau de bord (dashboard/student || dashboard/company)
            return new JsonResponse([
                'token' => $token,
                'role' => $user->getRoles()[1] ?? 'ROLE_USER',
                'redirect' => '/dashboard/' . $user->getRole(),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        try {
            // Récupération des informations de la requte
            $data = json_decode($request->getContent(), true);
            $email = $data['email'];
            $password = $data['password'];

            // On compare dans la bdd
            $user = $em->getRepository(Users::class)->findOneBy(['email' => $email]);

            if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
                return new JsonResponse(['error' => 'Invalid credentials'], 401);
            }

            // Génération du token JWT
            $jwtKey = $this->getParameter('jwt_key');
            $payload = [
                'user_id' => $user->getId(),
                'role' => $user->getRoles()[1] ?? 'ROLE_USER',
                'exp' => time() + 3600,
            ];

            $token = JWT::encode($payload, $jwtKey, 'HS256');

            return new JsonResponse([
                'token' => $token,
                'role' => $user->getRoles()[1] ?? 'ROLE_USER',
                'redirect' => '/dashboard/' . $user->getRole(),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/forgot-password', name: 'forgot_password', methods: ['POST'])]
    public function forgotPassword(Request $request, EntityManagerInterface $em, MailerService $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];

        $user = $em->getRepository(Users::class)->findOneBy(['email' => $email]);
        if (!$user) {
            return new JsonResponse(['error' => 'Email non trouvé'], 404);
        }

        $token = bin2hex(random_bytes(4)); 
        $user->setResetPassword($token);
        $user->setResetPasswordRequestedAt(new \DateTimeImmutable());
        $em->persist($user);
        $em->flush();

        $mailer->sendResetPasswordEmail($email, $token);

        return new JsonResponse(['message' => 'Email envoyé']);
    }

    #[Route('/reset-password', name: 'reset_password', methods: ['POST'])]
    public function resetPassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $token = $data['token'];
        $newPassword = $data['newPassword'];
        $user = $em->getRepository(Users::class)->findOneBy(['resetPassword' => $token]);
        if (!$user || !$user->getResetPasswordRequestedAt()) {
            return new JsonResponse(['error' => 'Token invalide'], 400);
        }

        $expiresAt = $user->getResetPasswordRequestedAt()->modify('+10 minutes');
        if (new \DateTimeImmutable() > $expiresAt) {
            return new JsonResponse(['error' => 'Token expiré'], 410);
        }

        $hashed = $hasher->hashPassword($user, $newPassword);
        $user->setPassword($hashed);
        $user->setResetPassword(null);
        $user->setResetPasswordRequestedAt(null);
        $em->persist($user);
        $em->flush();

        return new JsonResponse(['message' => 'Mot de passe mis à jour']);
    }
}
