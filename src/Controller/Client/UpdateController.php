<?php

namespace App\Controller\Client;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class UpdateController extends AbstractController
{
  #[Route('/api/clients/{id}', name: 'client_update', methods: ['PUT'])]
  public function updateClient(Client $currentClient, Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
  {
    /** @var User */
    $user = $this->getUser();

    if (!$user->getClients()->contains($currentClient)) {
      $data = ['message' => "Ce client n'est pas a vous !"];
      return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    $updateClient = $serializer->deserialize(
      $request->getContent(),
      Client::class,
      'json',
      [AbstractNormalizer::OBJECT_TO_POPULATE => $currentClient]
    );

    $em->persist($updateClient);
    $em->flush();

    return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }
}
