<?php

namespace App\Controller\Client;

use App\Entity\User;
use App\Entity\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShowController extends AbstractController
{

  #[Route('/api/clients/{id}', name: 'client_show', methods: ['GET'])]
  public function getClient(Client $client, SerializerInterface $serializer)
  {
    /** @var User */
    $user = $this->getUser();

    if (!$user->getClients()->contains($client)) {
      $data = [
        'message' => "Ce client n'est pas a vous !"
      ];
      return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    $jsonClient = $serializer->serialize($client, 'json', ['groups' => 'getClients']);
    return new JsonResponse($jsonClient, Response::HTTP_OK, [], true);
  }
}
