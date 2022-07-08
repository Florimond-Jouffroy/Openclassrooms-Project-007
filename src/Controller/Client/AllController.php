<?php

namespace App\Controller\Client;

use App\Entity\User;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AllController extends AbstractController
{

  #[Route('/api/clients', name: 'client_all', methods: ['GET'])]
  public function getAllClients(SerializerInterface $serializer): JsonResponse
  {
    /** @var User */
    $user = $this->getUser();
    $clientList = $user->getClients();
    $context = SerializationContext::create()->setGroups(['getClients']);
    $jsonClientList = $serializer->serialize($clientList, 'json', $context);

    return new JsonResponse($jsonClientList, Response::HTTP_OK, [], true);
  }
}
