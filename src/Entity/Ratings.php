<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RatingsRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiFilter(SearchFilter::class, properties: [
    'productId' => 'exact',
])]

#[ORM\Entity(repositoryClass: RatingsRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => ["ratings_read"]],
    denormalizationContext: ["groups" => ["ratings_write"]]
)]
class Ratings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["ratings_read"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["ratings_read", "ratings_write"])]
    private ?Product $productId = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    #[Groups(["ratings_read", "ratings_write"])]
    private ?User $userId = null;

    #[ORM\Column]
    #[Groups(["ratings_read", "ratings_write"])]
    private ?int $ratingValue = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["ratings_read", "ratings_write"])]
    private ?string $comment = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["ratings_read"])]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?Product
    {
        return $this->productId;
    }

    public function setProductId(?Product $productId): static
    {
        $this->productId = $productId;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getRatingValue(): ?int
    {
        return $this->ratingValue;
    }

    public function setRatingValue(int $ratingValue): static
    {
        $this->ratingValue = $ratingValue;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    #[Groups(["ratings_read"])]
    public function getUserName(): ?string
    {
        return $this->userId ? $this->userId->getFirstName() : null;
    }
}
