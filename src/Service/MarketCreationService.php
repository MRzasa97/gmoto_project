<?php

namespace App\Service;

use App\Repository\Interface\MarketRepositoryInterface;
use App\Entity\Market;

class MarketCreationService
{
    public function __construct(
        private readonly MarketRepositoryInterface $marketRepository
    ) {}

    public function getOrCreateMarket(string $marketCode): Market
    {
        $market = $this->marketRepository->findByCode($marketCode);
        if (!$market) {
            $market = new Market();
            $market->setCode($marketCode);
            $this->marketRepository->save($market);
        }
        return $market;
    }
}
