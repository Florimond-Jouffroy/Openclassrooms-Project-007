<?php

namespace App\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AllController extends AbstractController
{

  #[Route('/api/clients', name: 'client_all', methods: ['GET'])]
  public function getAllClients(SerializerInterface $serializer)
  {
  }
}
