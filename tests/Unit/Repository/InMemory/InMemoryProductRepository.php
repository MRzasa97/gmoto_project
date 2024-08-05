<?php

namespace App\Tests\Unit\Repository\InMemory;

use App\Entity\Product;
use App\Repository\Interface\ProductRepositoryInterface;

class InMemoryProductRepository implements ProductRepositoryInterface
{
    private array $products = [];

    public function save(Product $product, bool $flush = false): void
    {
        $this->products[$product->getGtin()] = $product;
    }

    public function findOneByGtin(string $gtin): ?Product
    {
        return $this->products[$gtin] ?? null;
    }

    public function findByMarketCode(string $marketCode): array
    {
        $result = [];
        foreach ($this->products as $product) {
            foreach ($product->getMarkets() as $market) {
                if ($market->getCode() === $marketCode) {
                    $result[] = $product;
                    break;
                }
            }
        }
        return $result;
    }
}
