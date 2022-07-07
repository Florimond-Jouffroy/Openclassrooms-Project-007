<?php

namespace App\Controller\Client;

use App\Entity\User;
use App\Entity\Client;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    $context = SerializationContext::create()->setGroups(['getClients']);
    $jsonClient = $serializer->serialize($client, 'json', $context);
    return new JsonResponse($jsonClient, Response::HTTP_OK, [], true);
  }
}
