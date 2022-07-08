<?php

namespace App\Controller\Client;

use App\Entity\User;
use App\Repository\ClientRepository;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class AllController extends AbstractController
{

  #[Route('/api/clients', name: 'client_all', methods: ['GET'])]
  public function getAllClients(SerializerInterface $serializer, Request $request, ClientRepository $clientRepository, TagAwareCacheInterface $cache): JsonResponse
  {
    /** @var User */
    $user = $this->getUser();

    $page = $request->get('page', 1);
    $limit = $request->get('limit', 5);

    $idCache = "getAllClients-" . $page . $limit;

    $jsonClientList = $cache->get($idCache, function (ItemInterface $item) use ($clientRepository, $page, $limit, $serializer, $user) {
      $item->tag("clientsCache");
      $clientList = $clientRepository->findAllWithPagination($page, $limit, $user->getId());
      $context = SerializationContext::create()->setGroups(['getClients']);
      return $serializer->serialize($clientList, 'json', $context);
    });

    return new JsonResponse($jsonClientList, Response::HTTP_OK, [], true);
  }
}
