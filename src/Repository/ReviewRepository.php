<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Review;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 *
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function findByProductSortedByDate(Product $product)
    {
        return $this->createQueryBuilder('r')
            ->where('r.product = :product')
            ->setParameter('product', $product)
            ->orderBy('r.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countReviewsByUser(User $user): int
    {
        return $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->where('r.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getAverageRatingPercentForProduct(Product $product)
    {
        $result = $this->createQueryBuilder('r')
            ->select('AVG(r.rating) as averageRating', 'COUNT(r.id) as reviewCount')
            ->where('r.product = :product')
            ->setParameter('product', $product)
            ->getQuery()
            ->getOneOrNullResult();

        if ($result) {
            $result['averageRatingPercent'] = ($result['averageRating'] / 5) * 100; // Conversion en pourcentage
            $result['averageRatingPercent'] = number_format($result['averageRatingPercent'], 2); // Formatage avec 2 chiffres après la virgule
        }

        return $result;
    }

//    /**
//     * @return Review[] Returns an array of Review objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Review
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
