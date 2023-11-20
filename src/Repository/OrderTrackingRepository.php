<?php

namespace App\Repository;

use App\Entity\OrderTracking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderTracking|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderTracking|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderTracking[]    findAll()
 * @method OrderTracking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderTrackingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderTracking::class);
    }

    public function findByOrder($orderId)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.order = :val')
            ->setParameter('val', $orderId)
            ->orderBy('o.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}