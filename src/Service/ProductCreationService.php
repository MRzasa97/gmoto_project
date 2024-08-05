<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\Interface\ProductRepositoryInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Log\LoggerInterface;

class ProductCreationService
{

    public function __construct(
        public readonly ProductRepositoryInterface $productRepository,
        public readonly MarketCreationService $marketCreationService,
        public readonly LoggerInterface $logger
    )
    {}

    public function createProduct(array $productData): void
    {
        $product = new Product();
        $product->setGtin($productData['data']['id'])
                ->setName($productData['data']['attributes']['name'])
                ->setDescription($productData['data']['attributes']['description'])
                ->setBrand($productData['data']['attributes']['brandName'])
                ->setStatus($productData['data']['attributes']['status']);

        foreach ($productData['data']['attributes']['targetMarket'] as $marketCode) {
            $market = $this->marketCreationService->getOrCreateMarket($marketCode);
            $product->addMarket($market);
        }

        try {
            $this->productRepository->save($product, true);
        } catch (UniqueConstraintViolationException $e) {
            $this->logger->error('Failed to create product: Product already exists.', [
                'gtin' => $product->getGtin(),
                'error' => $e->getMessage()
            ]);
            throw new \RuntimeException("Product already exists in the database.", 0, $e);
        }
    }
}