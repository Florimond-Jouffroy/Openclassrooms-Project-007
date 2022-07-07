<?php

namespace App\Controller\Client;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateController extends AbstractController
{

  #[Route('/api/clients', name: "clients_create", methods: ['POST'])]
  public function createClient(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator)
  {
    /** @var Client */
    $client = $serializer->deserialize($request->getContent(), Client::class, 'json');
    $client->setUser($this->getUser());

    $em->persist($client);
    $em->flush();

    $jsonClient = $serializer->serialize($client, 'json', ['groups' => 'getClients']);

    $location = $urlGenerator->generate('client_show', ['id' => $client->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

    return new JsonResponse($jsonClient, Response::HTTP_OK, ['Location' => $location], true);
  }
}
