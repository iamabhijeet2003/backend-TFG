<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
// #[ApiResource(
//     normalizationContext: ['groups' => ['order:read']],
//     denormalizationContext: ['groups' => ['order:create', 'order:update','order:write']],
// )]
#[ApiResource]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    // #[Groups(['order:read'])] 
    private ?int $id = null;

    #[ORM\Column]
    // #[Groups(['order:read', 'order:write','order:create'])] 
    private ?float $total = null;

    #[ORM\Column]
    // #[Groups(['order:read', 'order:write','order:create'])] 
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    // #[Groups(['order:read', 'order:write'])] 
    private ?User $user_id = null;

    #[ORM\OneToMany(targetEntity: OrderDetails::class, mappedBy: 'orderId', orphanRemoval: true)]
    private Collection $orderDetails;

    #[ORM\Column(nullable: true)]
    // #[Groups(['order:read', 'order:write'])] 
    private ?bool $prepared = null;

    #[ORM\Column(length: 255, nullable: true)]
    // #[Groups(['order:read', 'order:write'])] 
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    // #[Groups(['order:read', 'order:write'])] 
    private ?string $city = null;

    #[ORM\Column(nullable: true)]
    // #[Groups(['order:read', 'order:write'])] 
    private ?int $postal_code = null;

    #[ORM\Column(length: 255, nullable: true)]
    // #[Groups(['order:read', 'order:write'])] 
    private ?string $country = null;

    #[ORM\Column(nullable: true)]
    // #[Groups(['order:read', 'order:write'])] 
    private ?int $mobile = null;

    #[ORM\Column(length: 255, nullable: true)]
    // #[Groups(['order:read', 'order:write'])] 
    private ?string $province = null;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Collection<int, OrderDetails>
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetails $orderDetail): static
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails->add($orderDetail);
            $orderDetail->setOrderId($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetails $orderDetail): static
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getOrderId() === $this) {
                $orderDetail->setOrderId(null);
            }
        }

        return $this;
    }

    public function isPrepared(): ?bool
    {
        return $this->prepared;
    }

    public function setPrepared(?bool $prepared): static
    {
        $this->prepared = $prepared;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postal_code;
    }

    public function setPostalCode(?int $postal_code): static
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getMobile(): ?int
    {
        return $this->mobile;
    }

    public function setMobile(?int $mobile): static
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(?string $province): static
    {
        $this->province = $province;

        return $this;
    }
}
