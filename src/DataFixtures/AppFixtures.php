<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Mobile;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
  public function __construct(private UserPasswordHasherInterface $passwordHashe)
  {
  }

  public function load(ObjectManager $manager): void
  {

    //User
    $user = new User;
    $user
      ->setEmail("admin@bilemo.com")
      ->setPassword($this->passwordHashe->hashPassword($user, "admin"));
    $manager->persist($user);

    //Mobiles
    for ($i = 0; $i < 10; $i++) {
      $mobile = new Mobile;
      $mobile
        ->setBrand("Brand" . $i)
        ->setModel("Model XY-" . $i)
        ->setDescription("Descrition du produit")
        ->setPrice(mt_rand(260, 400))
        ->setStock(mt_rand(0, 100));
      $manager->persist($mobile);

      $client = new Client;
      $client
        ->setEmail('test' . $i . '@bilemo.com')
        ->setFirstname('prénom' . $i)
        ->setLastname('Nom' . $i);
      $manager->persist($client);
    }

    $manager->flush();
  }
}
