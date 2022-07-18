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
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

class ShowController extends AbstractController
{

  /**
   * Cette méthode permet de récupérer les infos d'un client liée à votre compte.
   *
   * @OA\Response(
   *     response=200,
   *     description="Retourne les infos de votre client!",
   *     @OA\JsonContent(
   *        type="array",
   *        @OA\Items(ref=@Model(type=Client::class, groups={"getClients"}))
   *     )
   * )
   *
   * @OA\Parameter(
   *     name="id",
   *     in="path",
   *     description="Id de l'élément que l'on veut récupérer",
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
   *  @OA\Response(
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
   * @param SerializerInterface $serializer
   * @param Client $currentClient
   * @return JsonResponse
   */
  #[Route('/api/clients/{id}', name: 'client_show', methods: ['GET'])]
  public function getClient(Client $client, SerializerInterface $serializer): JsonResponse
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
