<?php

namespace App\Security\Authentication;

use DateTime;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler  implements AuthenticationSuccessHandlerInterface
{
  public function __construct(private string $jwtSecret)
  {
  }
  public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
  {

    $user = $token->getUser();


    $user = [
      'sub' => $user->getUserIdentifier(),
    ];

    $jwt = JWT::encode($user, $this->jwtSecret, 'HS256');

    $now = new DateTime();
    $expires = $now->modify('+ 15 minutes')->getTimestamp();

    return new JsonResponse(['accessToken' => $jwt, 'expires' => $expires]);
  }
}
