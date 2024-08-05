<?php

namespace App\Repository\Interface;

use App\Entity\Market;

interface MarketRepositoryInterface
{
    public function save(Market $market, bool $flush = false): void;
    public function findByCode(string $code): ?Market;
}