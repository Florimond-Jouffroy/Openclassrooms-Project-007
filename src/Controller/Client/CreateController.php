<?php

namespace App\Controller\Client;

use App\Entity\Client;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

class CreateController extends AbstractController
{
  /**
   * Cette méthode permet de créer un client liée à votre compte.
   *
   * @OA\Response(
   *     response=201,
   *     description="Le client a bien été ajouter!",
   *     @OA\JsonContent(
   *        type="array",
   *        @OA\Items(ref=@Model(type=Client::class, groups={"getClients"}))
   *     )
   * )
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
   *
   * @OA\Tag(name="Client")
   *
   * @param SerializerInterface $serializer
   * @param Request $request
   * @param EntityManagerInterface $em
   * @param UrlGeneratorInterface $urlGenerator
   * @param ValidatorInterface $validator
   * @return JsonResponse
   */
  #[Route('/api/clients', name: "client_create", methods: ['POST'])]
  public function createClient(
    Request $request,
    SerializerInterface $serializer,
    EntityManagerInterface $em,
    UrlGeneratorInterface $urlGenerator,
    ValidatorInterface $validator
  ): JsonResponse {

    /** @var Client */
    $client = $serializer->deserialize($request->getContent(), Client::class, 'json');
    $client->setUser($this->getUser());

    $errors = $validator->validate($client);

    if ($errors->count() > 0) {
      return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST);
    }

    $em->persist($client);
    $em->flush();

    $context = SerializationContext::create()->setGroups(['getClients']);
    $jsonClient = $serializer->serialize($client, 'json', $context);

    $location = $urlGenerator->generate('client_show', ['id' => $client->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

    return new JsonResponse($jsonClient, Response::HTTP_OK, ['Location' => $location], true);
  }
}
