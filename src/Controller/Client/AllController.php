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
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

class AllController extends AbstractController
{

  /**
   * Cette méthode permet de récupérer l'ensemble des clients liée à vote compte.
   *
   * @OA\Response(
   *     response=200,
   *     description="Retourne la liste des clients",
   *     @OA\JsonContent(
   *        type="array",
   *        @OA\Items(ref=@Model(type=Client::class, groups={"getClients"}))
   *     )
   * )
   * @OA\Parameter(
   *     name="page",
   *     in="query",
   *     description="La page que l'on veut récupérer",
   *     @OA\Schema(type="int")
   * )
   *
   * @OA\Parameter(
   *     name="limit",
   *     in="query",
   *     description="Le nombre d'éléments que l'on veut récupérer",
   *     @OA\Schema(type="int")
   * )
   *
   * @OA\Response(
   *     response = 401,
   *     description = "Vous devez utiliser un token valide pour compléter cette demande"
   * )
   *
   *  @OA\Response(
   *     response = 403,
   *     description = "Accès interdit à ce contenu"
   * )
   *
   *
   * @OA\Tag(name="Client")
   *
   * @param ClientRepository $clientRepository
   * @param SerializerInterface $serializer
   * @param Request $request
   * @param TagAwareCacheInterface $cache
   * @return JsonResponse
   */
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
