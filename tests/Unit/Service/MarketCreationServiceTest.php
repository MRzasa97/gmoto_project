<?php

namespace App\Tests\Unit\Service;

use App\Entity\Market;
use App\Repository\Interface\MarketRepositoryInterface;
use App\Service\MarketCreationService;
use App\Tests\Unit\Repository\InMemory\InMemoryMarketRepository;
use PHPUnit\Framework\TestCase;

class MarketCreationServiceTest extends TestCase
{
    private MarketRepositoryInterface $marketRepository;
    private MarketCreationService $marketCreationService;

    protected function setUp(): void
    {
        $this->marketRepository = new InMemoryMarketRepository();
        $this->marketCreationService = new MarketCreationService($this->marketRepository);
    }

    public function testGetOrCreateMarket_CreatesNewMarket(): void
    {
        $marketCode = 'US';

        $market = $this->marketCreationService->getOrCreateMarket($marketCode);

        $this->assertNotNull($market);
        $this->assertSame($marketCode, $market->getCode());

        $savedMarket = $this->marketRepository->findByCode($marketCode);
        $this->assertSame($market, $savedMarket);
    }

    public function testGetOrCreateMarket_ReturnsExistingMarket(): void
    {
        $marketCode = 'US';
        $existingMarket = new Market();
        $existingMarket->setCode($marketCode);
        $this->marketRepository->save($existingMarket);

        $market = $this->marketCreationService->getOrCreateMarket($marketCode);

        $this->assertSame($existingMarket, $market);
    }
}
