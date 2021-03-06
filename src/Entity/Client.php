<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use JMS\Serializer\Annotation\Groups;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "client_show",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getClients")
 * )
 *
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "client_delete",
 *          parameters = { "id" = "expr(object.getId())" },
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getClients"),
 * )
 *
 * @Hateoas\Relation(
 *      "update",
 *      href = @Hateoas\Route(
 *          "client_update",
 *          parameters = { "id" = "expr(object.getId())" },
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getClients"),
 * )
 */
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
  #[Assert\NotBlank(message: "Le prénom du client doit être renseigner !")]
  #[Assert\Length(
    min: 2,
    minMessage: 'Le prénom du client doit faire plus de {{ limit }} caractères !',
    max: 255,
    maxMessage: 'Le prénom du client ne doit pas faire plus de {{ limit }} caractères !'
  )]
  private $firstname;

  #[ORM\Column(type: 'string', length: 255)]
  #[Groups(["getClients"])]
  #[Assert\NotBlank(message: "Le nom du client doit être renseigner !")]
  #[Assert\Length(
    min: 2,
    minMessage: 'Le nom du client doit faire plus de {{ limit }} caractères !',
    max: 255,
    maxMessage: 'Le nom du client ne doit pas faire plus de {{ limit }} caractères !'
  )]
  private $lastname;

  #[ORM\Column(type: 'string', length: 255)]
  #[Groups(["getClients"])]
  #[Assert\NotBlank(message: 'l\'email doit etre renseigner !')]
  #[Assert\Email(message: 'l\'email {{ value }} n\'est pas valide !')]
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
