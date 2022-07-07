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

class CreateController extends AbstractController
{
  #[Route('/api/clients', name: "client_create", methods: ['POST'])]
  public function createClient(
    Request $request,
    SerializerInterface $serializer,
    EntityManagerInterface $em,
    UrlGeneratorInterface $urlGenerator,
    ValidatorInterface $validator
  ) {

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
