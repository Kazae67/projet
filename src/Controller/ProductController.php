<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Entity\Product; // Assurez-vous d'importer l'entité Product
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'product')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products
        ]);
    }

    // Nouvelle action pour afficher un produit en détail
    #[Route('/product/{id}', name: 'product_show')]
    public function show(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Le produit demandé n\'existe pas.');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
