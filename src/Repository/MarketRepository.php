<?php

namespace App\Repository;

use App\Entity\Market;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Interface\MarketRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Market>
 */
class MarketRepository implements MarketRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function save(Market $market, bool $flush = false): void
    {
        $this->entityManager->persist($market);
        if($flush) {
            $this->entityManager->flush();
        }
    }

    public function findByCode(string $code): ?Market
    {
        return $this->entityManager->createQueryBuilder()
            ->select('m')
            ->from(Market::class, 'm')
            ->where('m.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
