<?php

namespace App\Repository\Interface;

use App\Entity\Product;

interface ProductRepositoryInterface
{
    public function save(Product $product, bool $flush = false): void;
    public function findOneByGtin(string $gtin): ?Product;
    public function findByMarketCode(string $marketCode): array;
}