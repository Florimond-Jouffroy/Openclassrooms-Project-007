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

class AllController extends AbstractController
{

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
