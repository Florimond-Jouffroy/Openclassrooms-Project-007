<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  #[Groups(["getClients"])]
  private $id;

  #[ORM\Column(type: 'string', length: 255)]
  #[Groups(["getClients"])]
  private $firstname;

  #[ORM\Column(type: 'string', length: 255)]
  #[Groups(["getClients"])]
  private $lastname;

  #[ORM\Column(type: 'string', length: 255)]
  #[Groups(["getClients"])]
  private $email;

  #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'clients')]
  #[ORM\JoinColumn(nullable: false)]
  private $user;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getFirstname(): ?string
  {
    return $this->firstname;
  }

  public function setFirstname(string $firstname): self
  {
    $this->firstname = $firstname;

    return $this;
  }

  public function getLastname(): ?string
  {
    return $this->lastname;
  }

  public function setLastname(string $lastname): self
  {
    $this->lastname = $lastname;

    return $this;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }

  public function getUser(): ?User
  {
    return $this->user;
  }

  public function setUser(?User $user): self
  {
    $this->user = $user;

    return $this;
  }
}
