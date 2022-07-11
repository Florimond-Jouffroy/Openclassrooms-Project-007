<?php

namespace App\Security\Authentication;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class CustomAuthenticator implements AuthenticatorInterface
{

  public function __construct(private string $jwtSecret)
  {
  }

  public function supports(Request $request): ?bool
  {
    return $request->headers->has('Authorization');
  }

  public function authenticate(Request $request) /*: Passport;*/
  {
    $tokenHeader = $request->headers->get('Authorization');

    if (null === $tokenHeader or "" === $tokenHeader) {
      throw new CustomUserMessageAuthenticationException('No API token provided');
    }

    $tokenData = str_replace('Bearer ', '', $tokenHeader);


    $token = JWT::decode($tokenData, new Key($this->jwtSecret, 'HS256'));

    $email = $token->sub;

    return new SelfValidatingPassport(new UserBadge($email));
  }

  public function createAuthenticatedToken(PassportInterface $passport, string $firewallName): TokenInterface
  {
    return new UsernamePasswordToken($passport->getUser(), null, $firewallName, $passport->getUser()->getRoles());
  }

  public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
  {
    return null;
  }

  public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?JsonResponse
  {
    $data = [
      'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
    ];
    return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
  }
}
