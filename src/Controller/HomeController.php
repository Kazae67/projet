<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(ProductRepository $productRepository): Response
    {
        // Récupère les 5 produits les mieux notés
        $topRatedProducts = $productRepository->findTopRatedProducts(5);
    
        // Récupère les 5 produits les plus vendus
        $topSellingProducts = $productRepository->findTopSellingProducts(5);
    
        // Récupère les 10 produits les plus récents
        $latestProducts = $productRepository->findLatestProducts(10);
    
        return $this->render('home/index.html.twig', [
            'topRatedProducts' => $topRatedProducts,
            'topSellingProducts' => $topSellingProducts,
            'latestProducts' => $latestProducts  // Ajout des produits les plus récents
        ]);
    }
}