<?php

namespace App\Repository;

use App\Entity\Product;
use App\Repository\Interface\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function save(Product $product, bool $flush = false): void
    {
        $this->entityManager->persist($product);
        if($flush) {
            $this->entityManager->flush();
        }
    }

    public function findOneByGtin(string $gtin): ?Product
    {
        return $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p')
            ->where('p.gtin = :gtin')
            ->setParameter('gtin', $gtin)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByMarketCode(string $marketCode): array
    {
        return $this->entityManager->createQueryBuilder()
        ->select('p')
        ->from(Product::class, 'p')
        ->join('p.markets', 'm')
        ->where('m.code = :marketCode')
        ->setParameter('marketCode', $marketCode)
        ->getQuery()
        ->getResult();
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
