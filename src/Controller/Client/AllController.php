<?php

namespace App\Controller\Client;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AllController extends AbstractController
{

  #[Route('/api/clients', name: 'client_all', methods: ['GET'])]
  public function getAllClients(SerializerInterface $serializer): JsonResponse
  {
    /** @var User */
    $user = $this->getUser();
    $clientList = $user->getClients();
    $jsonClientList = $serializer->serialize($clientList, 'json', ['groups' => 'getClients']);

    return new JsonResponse($jsonClientList, Response::HTTP_OK, [], true);
  }
}
