<?php

namespace App\Controller\Client;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UpdateController extends AbstractController
{
  #[Route('/api/clients/{id}', name: 'client_update', methods: ['PUT'])]
  public function updateClient(Client $currentClient, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
  {
    /** @var User */
    $user = $this->getUser();

    if (!$user->getClients()->contains($currentClient)) {
      $data = ['message' => "Ce client n'est pas a vous !"];
      return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /** @var Client */
    $newClient = $serializer->deserialize($request->getContent(), Client::class, 'json');

    if ($newClient->getFirstname() !== null) {
      $currentClient->setFirstname($newClient->getFirstname());
    }
    if ($newClient->getLastname() !== null) {
      $currentClient->setLastname($newClient->getLastname());
    }
    if ($newClient->getEmail() !== null) {
      $currentClient->setEmail($newClient->getEmail());
    }

    $errors = $validator->validate($currentClient);

    if ($errors->count() > 0) {
      return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST);
    }

    $em->persist($currentClient);
    $em->flush();

    return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }
}
