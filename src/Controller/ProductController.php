<?php
// src/Controller/ProductController.php

namespace App\Controller;

use App\Repository\Interface\ProductRepositoryInterface;
use App\Service\ProductSerializerService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProductController extends AbstractController
{
    public function __construct(
        public readonly ProductRepositoryInterface $productRepository,
    )
    {}


    #[Route("/api/products/{gtin}", name: "get_product", methods: ["GET"])]
    public function getProduct(string $gtin): JsonResponse
    {
        $product = $this->productRepository->findOneByGtin($gtin);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], 404);
        }

        return $this->json($product, 200, [], ['groups' => 'product:read']);
    }

    #[Route("/api/products/market/{marketCode}", name: "get_products_by_market", methods: ["GET"])]
    public function getProductsByMarketCode(string $marketCode): JsonResponse
    {
        $products = $this->productRepository->findByMarketCode($marketCode);

        if (empty($products)) {
            return new JsonResponse(['error' => 'No products found for this market'], 404);
        }

        return $this->json($products, 200, [], ['groups' => 'product:read']);
    }
}
