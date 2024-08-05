<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:read'])]
    private ?string $gtin = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:read'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:read'])]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:read'])]
    private ?string $status = null;

    /**
     * @var Collection<int, Market>
     */
    #[ORM\ManyToMany(targetEntity: Market::class, mappedBy: 'products', cascade: ["persist"])]
    #[Groups(['product:read'])]
    private Collection $markets;

    public function __construct()
    {
        $this->markets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getGtin(): ?string
    {
        return $this->gtin;
    }

    public function setGtin(string $gtin): static
    {
        $this->gtin = $gtin;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Market>
     */
    public function getMarkets(): Collection
    {
        return $this->markets;
    }

    public function addMarket(Market $market): static
    {
        if (!$this->markets->contains($market)) {
            $this->markets->add($market);
            $market->addProduct($this);
        }

        return $this;
    }

    public function removeMarket(Market $market): static
    {
        if ($this->markets->removeElement($market)) {
            $market->removeProduct($this);
        }

        return $this;
    }
}
