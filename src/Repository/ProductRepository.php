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


    // Méthode pour compter les produits filtrés avec prise en compte de la tranche de prix
    public function countFilteredProducts($category = null, $priceMin = null, $priceMax = null)
    {
        // Création d'une requête QueryBuilder avec 'p' comme alias pour l'entité produit
        $qb = $this->createQueryBuilder('p');

        // Filtrage des produits actifs
        $qb->where('p.is_active = :isActive')
        ->setParameter('isActive', true);

        // Ajout d'un filtre de catégorie, si spécifié
        if ($category) {
            $qb->andWhere('p.category = :category')
            ->setParameter('category', $category);
        }

        // Ajout de filtres pour la tranche de prix minimum, si spécifiée
        if ($priceMin !== null && $priceMin !== '') {
            $qb->andWhere('p.price >= :priceMin')
            ->setParameter('priceMin', $priceMin);
        }

        // Ajout de filtres pour la tranche de prix maximum, si spécifiée
        if ($priceMax !== null && $priceMax !== '') {
            $qb->andWhere('p.price <= :priceMax')
            ->setParameter('priceMax', $priceMax);
        }

        // Renvoie le nombre total de produits qui correspondent aux critères de filtre
        return $qb->select('count(p.id)')
                ->getQuery()
                ->getSingleScalarResult();
    }

    // Méthode pour récupérer les produits avec filtres, tri et tranche de prix
    public function findByFilters($category = null, $sort = 'newest', $maxResults, $start, $priceMin = null, $priceMax = null)
    {
        // Création d'une requête QueryBuilder avec 'p' comme alias pour l'entité produit
        $qb = $this->createQueryBuilder('p');

        // Filtrage des produits actifs
        $qb->where('p.is_active = :isActive')
        ->setParameter('isActive', true);

        // Ajout d'un filtre de catégorie, si spécifié
        if ($category) {
            $qb->andWhere('p.category = :category')
            ->setParameter('category', $category);
        }

        // Ajout de filtres pour la tranche de prix minimum, si spécifiée
        if ($priceMin !== null && $priceMin !== '') {
            $qb->andWhere('p.price >= :priceMin')
            ->setParameter('priceMin', $priceMin);
        }

        // Ajout de filtres pour la tranche de prix maximum, si spécifiée
        if ($priceMax !== null && $priceMax !== '') {
            $qb->andWhere('p.price <= :priceMax')
            ->setParameter('priceMax', $priceMax);
        }

        // Gestion du tri des produits selon le critère spécifié
        switch ($sort) {
            case 'oldest':
                $qb->orderBy('p.created_at', 'ASC');
                break;
            case 'price_high_to_low':
                $qb->orderBy('p.price', 'DESC');
                break;
            case 'price_low_to_high':
                $qb->orderBy('p.price', 'ASC');
                break;
            default:
                $qb->orderBy('p.created_at', 'DESC');
                break;
        }

        // Application de la pagination avec le nombre maximal de résultats et le point de départ
        return $qb->setMaxResults($maxResults)
                ->setFirstResult($start)
                ->getQuery()
                ->getResult();
    }

    // Compter le nombre de fois qu'un produit a été vendu
        public function countSalesForProduct(Product $product): int
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT COUNT(od.id)
                FROM App\Entity\OrderDetail od
                WHERE od.product = :product'
            )
            ->setParameter('product', $product)
            ->getSingleScalarResult();
    }
    public function findTopRatedProducts($limit = 5)
    {
        return $this->createQueryBuilder('p')
            ->addSelect('AVG(r.rating) as HIDDEN avgRating') // Calcule la moyenne des notes
            ->leftJoin('p.reviews', 'r') // Jointure avec la table des avis
            ->where('p.is_active = true') // Filtre pour inclure uniquement les produits actifs
            ->groupBy('p') // Groupe par produit
            ->orderBy('avgRating', 'DESC') // Trie par note moyenne décroissante
            ->setMaxResults($limit) // Limite à 5 produits
            ->getQuery()
            ->getResult();
    }

    public function findTopSellingProducts($limit = 5)
    {
        // Récupère les produits actifs
        $products = $this->findBy(['is_active' => true]);
    
        // Crée un tableau pour stocker les produits et leur nombre de ventes
        $productsWithSalesCount = [];
        foreach ($products as $product) {
            $salesCount = $this->countSalesForProduct($product);
            $productsWithSalesCount[] = [
                'product' => $product,
                'salesCount' => $salesCount
            ];
        }
    
        // Trie le tableau par le nombre de ventes en ordre décroissant
        usort($productsWithSalesCount, function ($a, $b) {
            return $b['salesCount'] <=> $a['salesCount'];
        });
    
        // Retourne les $limit premiers produits
        return array_slice($productsWithSalesCount, 0, $limit);
    }

    public function findLatestProducts($limit = 10)
    {
        return $this->createQueryBuilder('p')
            ->where('p.is_active = true') // Assurez-vous que le produit est actif
            ->orderBy('p.created_at', 'DESC') // Trie par date de création, remplacez 'createdAt' par votre champ de date réel
            ->setMaxResults($limit)
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
