<?php

namespace App\Tests\Unit\Service;

use App\Entity\Product;
use App\Entity\Market;
use App\Repository\Interface\ProductRepositoryInterface;
use App\Repository\MarketRepository;
use App\Repository\ProductRepository;
use App\Service\MarketCreationService;
use App\Service\ProductCreationService;
use App\Tests\Unit\Repository\InMemory\InMemoryProductRepository;
use App\Tests\Unit\Repository\InMemory\InMemoryMarketRepository;
use PHPUnit\Framework\TestCase;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Driver\AbstractDriverException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ProductCreationServiceTest extends TestCase
{
    private ProductRepositoryInterface $productRepository;
    private MarketCreationService $marketCreationService;
    private ProductCreationService $productCreationService;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->productRepository = new InMemoryProductRepository();
        $marketRepository = new InMemoryMarketRepository();

        $this->marketCreationService = new MarketCreationService($marketRepository);
        $this->logger = new NullLogger();
        $this->productCreationService = new ProductCreationService(
            $this->productRepository,
            $this->marketCreationService,
            $this->logger
        );
    }

    public function testCreateProduct(): void
    {
        $productData = [
            'data' => [
                'id' => '05905295000023',
                'attributes' => [
                    'name' => 'Test Product',
                    'description' => 'Test Description',
                    'brandName' => 'Test Brand',
                    'status' => 'ACTIVE',
                    'targetMarket' => ['US', 'EU']
                ]
            ]
        ];

        $this->productCreationService->createProduct($productData);

        $savedProduct = $this->productRepository->findOneByGtin($productData['data']['id']);
        $this->assertNotNull($savedProduct);
        $this->assertSame($productData['data']['id'], $savedProduct->getGtin());
        $this->assertSame($productData['data']['attributes']['name'], $savedProduct->getName());
        $this->assertSame($productData['data']['attributes']['description'], $savedProduct->getDescription());
        $this->assertSame($productData['data']['attributes']['brandName'], $savedProduct->getBrand());
        $this->assertSame($productData['data']['attributes']['status'], $savedProduct->getStatus());

        $markets = $savedProduct->getMarkets();
        $this->assertCount(2, $markets);
        $this->assertSame('US', $markets[0]->getCode());
        $this->assertSame('EU', $markets[1]->getCode());
    }
}
