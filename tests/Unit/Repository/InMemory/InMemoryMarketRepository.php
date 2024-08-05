<?php

namespace App\Tests\Unit\Repository\InMemory;

use App\Entity\Market;
use App\Repository\Interface\MarketRepositoryInterface;

class InMemoryMarketRepository implements MarketRepositoryInterface
{
    private array $markets = [];

    public function save(Market $market, bool $flush = false): void
    {
        $this->markets[$market->getCode()] = $market;
    }

    public function findByCode(string $code): ?Market
    {
        return $this->markets[$code] ?? null;
    }
}
