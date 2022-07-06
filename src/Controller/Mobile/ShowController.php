<?php

namespace App\Controller\Mobile;

use App\Entity\Mobile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ShowController extends AbstractController
{
  #[Route('/api/mobile/{id}', name: 'mobile_show', methods: ['GET'])]
  public function getMobile(Mobile $mobile, SerializerInterface $serializer)
  {
    $jsonMobile = $serializer->serialize($mobile, 'json');
    return new JsonResponse($jsonMobile, Response::HTTP_OK, [], true);
  }
}
