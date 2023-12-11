<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }
        /**
     * Find orders sold by a given user.
     *
     * @param User $user
     * @return Order[]
     */
    public function findSoldOrdersByUser($user)
    {
        return $this->createQueryBuilder('o')
            ->join('o.orderDetails', 'od')
            ->join('od.product', 'p')
            ->where('p.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function findSoldOrderByTokenAndSeller(string $token, User $seller): ?Order
{
    return $this->createQueryBuilder('o')
        ->innerJoin('o.orderDetails', 'od')
        ->innerJoin('od.product', 'p')
        ->where('o.trackingToken = :token')
        ->andWhere('p.user = :seller')
        ->setParameter('token', $token)
        ->setParameter('seller', $seller)
        ->getQuery()
        ->getOneOrNullResult();
}
    
//    /**
//     * @return Order[] Returns an array of Order objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Order
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
