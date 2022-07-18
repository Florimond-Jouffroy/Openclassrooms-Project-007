<?php

namespace App\Controller\Client;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

class DeleteController extends AbstractController
{
  /**
   * Cette méthode permet de supprimer un client liée à votre compte.
   *
   * @OA\Response(
   *     response=204,
   *     description="Le client à bien été supprimer !",
   *     @OA\JsonContent(
   *        type="array",
   *        @OA\Items(ref=@Model(type=Phone::class))
   *     )
   * )
   *
   * @OA\Parameter(
   *     name="id",
   *     in="path",
   *     description="Id de l'élément que l'on veut supprimer",
   *     required=true,
   *     @OA\Schema(
   *          type="int",
   *          format="int64"
   *      )
   * )
   *
   * @OA\Response(
   *     response = 401,
   *     description = "Vous devez utiliser un token valide pour compléter cette demande"
   * )
   *
   * @OA\Response(
   *     response = 403,
   *     description = "Accès interdit à ce contenu"
   * )
   *
   * @OA\Response(
   *     response = 404,
   *     description = "Cette ressource n'existe pas !"
   * )
   *
   * @OA\Tag(name="Client")
   *
   * @param Client $client
   * @param EntityManagerInterface $em
   * @param TagAwareCacheInterface $cachePool
   * @return JsonResponse
   */
  #[Route('/api/clients/{id}', name: 'client_delete', methods: ['DELETE'])]
  public function deleteClient(Client $client, EntityManagerInterface $em, TagAwareCacheInterface $cachePool)
  {
    /** @var User */
    $user = $this->getUser();

    if (!$user->getClients()->contains($client)) {
      $data = ['message' => "Ce client n'est pas a vous !"];
      return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    $cachePool->invalidateTags(["clientsCache"]);

    $em->remove($client);
    $em->flush();

    return new JsonResponse(null, Response::HTTP_NO_CONTENT);
  }
}
