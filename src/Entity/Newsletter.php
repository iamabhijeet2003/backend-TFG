<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\NewsletterRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

#[ORM\Entity(repositoryClass: NewsletterRepository::class)]
#[ApiResource(
    operations: [
        new Get(security: "is_granted('ROLE_ADMIN')",securityMessage: 'Only admin have persmission to access this resource.'),
        new GetCollection(security: "is_granted('ROLE_ADMIN')",securityMessage: 'Only admins can see all the newsletter subscribers.'),
        new Post(),
    ],
    paginationEnabled: false
)]
class Newsletter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
}
