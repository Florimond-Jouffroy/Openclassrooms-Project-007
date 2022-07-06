<?php

namespace App\Security\Authentication;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class AuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
  public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
  {
    return new JsonResponse([
      'message' => $exception->getMessage()
    ], Response::HTTP_UNAUTHORIZED);
  }
}
