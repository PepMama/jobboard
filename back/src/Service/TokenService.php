<?php 

namespace App\Service;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class TokenService
{
    private $em;
    private $jwtKey;

    public function __construct(EntityManagerInterface $em, string $jwtKey)
    {
        $this->em = $em;
        $this->jwtKey = $jwtKey;
    }

    public function getUserFromRequest(Request $request): Users
    {
        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            throw new UnauthorizedHttpException('Bearer', 'Token manquant ou invalide');
        }

        $token = substr($authHeader, 7);

        try {
            $decoded = JWT::decode($token, new Key($this->jwtKey, 'HS256'));
            $userId = $decoded->user_id ?? null;

            if (!$userId) {
                throw new \Exception('ID utilisateur introuvable dans le token');
            }

            $user = $this->em->getRepository(Users::class)->find($userId);

            if (!$user) {
                throw new \Exception('Utilisateur non trouvé');
            }

            return $user;

        } catch (\Exception $e) {
            throw new UnauthorizedHttpException('Bearer', $e->getMessage());
        }
    }
}
