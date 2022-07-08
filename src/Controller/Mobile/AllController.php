<?php

namespace App\Controller\Mobile;

use App\Repository\MobileRepository;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AllController extends AbstractController
{

  #[Route('/api/mobiles', name: 'mobile_all', methods: ['GET'])]
  public function getAllMobiles(MobileRepository $mobileRepository, SerializerInterface $serializer)
  {
    $mobileList = $mobileRepository->findAll();
    $jsonMobileList = $serializer->serialize($mobileList, 'json');

    return new JsonResponse($jsonMobileList, Response::HTTP_OK, [], true);
  }
}
