<?php

namespace App\DataFixtures;

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

    $user = new User;
    $user
      ->setEmail("admin@bilemo.com")
      ->setPassword($this->passwordHashe->hashPassword($user, "admin"));

    $manager->persist($user);
    $manager->flush();
  }
}
