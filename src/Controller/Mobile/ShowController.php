<?php

namespace App\Controller\Mobile;

use App\Entity\Mobile;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

class ShowController extends AbstractController
{
  /**
   * Cette méthode permet de récupérer les infos d'un mobile.
   *
   * @OA\Response(
   *     response=200,
   *     description="Retourne les infos du mobile",
   *     @OA\JsonContent(
   *        type="array",
   *        @OA\Items(ref=@Model(type=Phone::class))
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
   * @OA\Tag(name="Mobile")
   *
   * @param Mobile $mobile
   * @param SerializerInterface $serializer
   * @return JsonResponse
   */
  #[Route('/api/mobiles/{id}', name: 'mobile_show', methods: ['GET'])]
  public function getMobile(Mobile $mobile, SerializerInterface $serializer)
  {
    $jsonMobile = $serializer->serialize($mobile, 'json');
    return new JsonResponse($jsonMobile, Response::HTTP_OK, [], true);
  }
}
