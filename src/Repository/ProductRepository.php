<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[] Returns an array of Product objects associated with the given user
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :user')
            ->setParameter('user', $user)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }


    // Méthode pour compter les produits filtrés
    public function countFilteredProducts($category = null)
    {
        $qb = $this->createQueryBuilder('p');

        if ($category) {
            $qb->andWhere('p.category = :category')
                ->setParameter('category', $category);
        }

        return $qb->select('count(p.id)')
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    // Méthode pour récupérer les produits avec filtres et tri
    public function findByFilters($category = null, $sort = 'newest', $maxResults, $start)
    {
        $qb = $this->createQueryBuilder('p');

        if ($category) {
            $qb->andWhere('p.category = :category')
                ->setParameter('category', $category);
        }

        if ($sort === 'oldest') {
            $qb->orderBy('p.created_at', 'ASC');
        } else {
            $qb->orderBy('p.created_at', 'DESC');
        }

        return $qb->setMaxResults($maxResults)
                    ->setFirstResult($start)
                    ->getQuery()
                    ->getResult();
    }

    // You can uncomment and use the following methods as examples for custom queries:

    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('p')
    //         ->andWhere('p.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('p.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }

    // public function findOneBySomeField($value): ?Product
    // {
    //     return $this->createQueryBuilder('p')
    //         ->andWhere('p.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }
}
