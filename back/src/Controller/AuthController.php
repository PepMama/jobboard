<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Student;
use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Firebase\JWT\JWT;

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
                'role' => $user->getRole(),
                'exp' => time() + 3600,
            ];
    
            $token = JWT::encode($payload, $jwtKey, 'HS256');
    
            // Rediriger sur le bon tableau de bord (dashboard/student || dashboard/company)
            return new JsonResponse([
                'token' => $token,
                'role' => $user->getRole(),
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
                'role' => $user->getRole(),
                'exp' => time() + 3600,
            ];

            $token = JWT::encode($payload, $jwtKey, 'HS256');

            return new JsonResponse([
                'token' => $token,
                'role' => $user->getRole(),
                'redirect' => '/dashboard/' . $user->getRole(),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

}
