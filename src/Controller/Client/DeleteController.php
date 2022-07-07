<?php

namespace App\Controller\Client;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeleteController extends AbstractController
{
  #[Route('/api/clients/{id}', name: 'client_delete', methods: ['DELETE'])]
  public function deleteClient(Client $client, EntityManagerInterface $em)
  {
    /** @var User */
    $user = $this->getUser();

    if (!$user->getClients()->contains($client)) {
      $data = ['message' => "Ce client n'est pas a vous !"];
      return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    $em->remove($client);
    $em->flush();

    return new JsonResponse(null, Response::HTTP_NO_CONTENT);
  }
}
