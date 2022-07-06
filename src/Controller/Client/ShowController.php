<?php

namespace App\Controller\Client;

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
    //todo vérifier que le client appartiens bien au current user;
    $jsonClient = $serializer->serialize($client, 'json');
    return new JsonResponse($jsonClient, Response::HTTP_OK, [], true);
  }
}
