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
    // Utilisation de createQueryBuilder pour créer une requête DQL en utilisant 'o' comme alias pour l'entité.
    return $this->createQueryBuilder('o')
        // Ajout d'une condition où l'attribut 'order' de l'entité représentée par l'alias 'o' doit correspondre à un paramètre nommé.
        ->andWhere('o.order = :val')
        // Affectation de la valeur de $orderId au paramètre nommé ':val'.
        // Doctrine s'occupe automatiquement d'échapper les valeurs pour éviter les injections SQL.
        ->setParameter('val', $orderId)
        // Tri des résultats par l'attribut 'updatedAt' de l'entité représentée par l'alias 'o' en ordre décroissant.
        ->orderBy('o.updatedAt', 'DESC')
        // Obtention de la requête DQL préparée.
        ->getQuery()
        // Exécution de la requête et récupération des résultats.
        ->getResult();
    }
    
}
