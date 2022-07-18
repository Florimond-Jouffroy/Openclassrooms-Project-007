<?php

namespace App\Controller\Mobile;

use App\Repository\MobileRepository;
use JMS\Serializer\SerializerInterface;
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
   * Cette méthode permet de récupérer l'ensemble des mobiles.
   *
   * @OA\Response(
   *     response=200,
   *     description="Retourne la liste des mobiles",
   *     @OA\JsonContent(
   *        type="array",
   *        @OA\Items(ref=@Model(type=Mobile::class, groups={"getMobiles"}))
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
   * @OA\Tag(name="Mobile")
   *
   * @param MobileRepository $mobileRepository
   * @param SerializerInterface $serializer
   * @param Request $request
   * @return JsonResponse
   */
  #[Route('/api/mobiles', name: 'mobile_all', methods: ['GET'])]
  public function getAllMobiles(MobileRepository $mobileRepository, SerializerInterface $serializer, Request $request, TagAwareCacheInterface $cache)
  {
    $page = $request->get('page', 1);
    $limit = $request->get('limit', 3);

    $idCache = "getAllMobiles-" . $page . "-" . $limit;

    $jsonMobileList = $cache->get($idCache, function (ItemInterface $item) use ($mobileRepository, $page, $limit, $serializer) {
      $item->tag('mobilesCache');
      $mobileList = $mobileRepository->findAllWithPagination($page, $limit);
      return $serializer->serialize($mobileList, 'json');
    });

    return new JsonResponse($jsonMobileList, Response::HTTP_OK, [], true);
  }
}
