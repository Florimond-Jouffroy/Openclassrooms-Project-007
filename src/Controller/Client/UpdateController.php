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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

class UpdateController extends AbstractController
{

  /**
   * Cette méthode permet de modifier un client liée à votre compte.
   *
   * @OA\Response(
   *     response=201,
   *     description="Le client a bien été modifier!",
   *     @OA\JsonContent(
   *        type="array",
   *        @OA\Items(ref=@Model(type=Client::class, groups={"getClients"}))
   *     )
   * )
   *
   * @OA\Parameter(
   *     name="id",
   *     in="path",
   *     description="Id de l'élément que l'on veut modifié",
   *     required=true,
   *     @OA\Schema(
   *          type="int",
   *          format="int64"
   *      )
   * )
   *
   * @OA\RequestBody(
   *    required=true,
   *    @OA\MediaType(
   *        mediaType="application/json",
   *        @OA\Schema(
   *            @OA\Property(property="firstname", type="string", example="Jhon"),
   *            @OA\Property(property="lastname", type="string", example="Doe"),
   *            @OA\Property(property="email", type="string",format="email", example="jhon.doe@bilemo.com")
   *       )
   *    )
   * )
   *
   * @OA\Response(
   *     response = 400,
   *     description = "Mauvaises données envoyées, vérifiez les champs et réessayez"
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
   * @param Request $request
   * @param EntityManagerInterface $em
   * @param Client $currentClient
   * @param ValidatorInterface $validator
   * @return JsonResponse
   */
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
