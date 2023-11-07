<?php
namespace App\Service;
use App\Repository\UserRepository;
use App\Service\UtilsService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class JwtService{
    private string $token;
    public function __construct(string $token, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepo){
        $this->token = $token;
    }
}