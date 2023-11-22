<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'product')]
    public function index(ProductRepository $productRepository): Response
    {
        // Récupérer tous les produits depuis le repository
        $products = $productRepository->findAll();

        // Rendre la vue 'product/index.html.twig' avec la liste des produits
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products
        ]);
    }

    // action pour afficher un produit en détail
    #[Route('/product/{id}', name: 'product_show', requirements: ['id' => '\d+'])]
    public function show(int $id, ProductRepository $productRepository): Response
    {
        // Trouver le produit par son ID
        $product = $productRepository->find($id);

        // Si le produit n'existe pas, générer une exception
        if (!$product) {
            throw $this->createNotFoundException('The requested product does not exist.');
        }

        // Rendre la vue 'product/show.html.twig' avec les détails du produit
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/add-to-cart/{id}', name: 'add_to_cart')]
    public function addToCart(int $id, CartService $cartService): Response
    {
        try {
            // Ajouter le produit au panier
            $cartService->add($id);
            $this->addFlash('success', 'Product added to cart.');
            return $this->redirectToRoute('cart_index');
        } catch (\Exception $e) {
            // En cas d'erreur, afficher un message d'erreur et rediriger vers la page de connexion
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/cart', name: 'cart_index')]
    public function cartIndex(CartService $cartService): Response
    {
        // Obtenir le contenu complet du panier et le total
        $cart = $cartService->getFullCart();
        $total = $cartService->getTotal();

        // Rendre la vue 'cart/index.html.twig' avec le contenu du panier et le total
        return $this->render('cart/index.html.twig', [
            'items' => $cart,
            'total' => $total,
        ]);
    }

    // Les méthodes pour gérer l'ajout, l'édition et la suppression de produits par un artisan sont annotées avec IsGranted pour restreindre l'accès aux artisans.
    #[IsGranted('ROLE_CRAFTSMAN')]
    #[Route('/product/add', name: 'product_add')]
    public function addProduct(Request $request, EntityManagerInterface $em, ProductRepository $productRepository): Response
    {
        // Créer un nouveau produit et un formulaire
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si un produit avec le même nom existe déjà
            $existingProduct = $productRepository->findOneBy(['name' => $product->getName()]);
            if ($existingProduct) {
                // Afficher un message d'erreur si le produit existe déjà
                $this->addFlash('error', 'A product with this name already exists.');
                return $this->render('product/add.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // Associer le produit à l'utilisateur connecté et le sauvegarder
            $product->setUser($this->getUser());
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product successfully added.');
            return $this->redirectToRoute('product');
        }

        // Rendre la vue 'product/add.html.twig' avec le formulaire
        return $this->render('product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_CRAFTSMAN')]
    #[Route('/my-products', name: 'my_products')]
    public function myProducts(ProductRepository $productRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found or not logged in.');
        }

        $products = $productRepository->findByUser($user);

        return $this->render('product/myProducts.html.twig', [
            'products' => $products
        ]);
    }

    #[IsGranted('ROLE_CRAFTSMAN')]
    #[Route('/product/edit/{id}', name: 'product_edit')]
    public function editProduct(Request $request, EntityManagerInterface $em, Product $product): Response
    {
        // Créer un formulaire de modification pour le produit
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications apportées au produit
            $em->flush();
            $this->addFlash('success', 'Product successfully updated.');

            return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
        }

        // Rendre la vue 'product/edit.html.twig' avec le formulaire de modification
        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_CRAFTSMAN')]
    #[Route('/product/delete/{id}', name: 'product_delete', methods: ['POST'])]
    public function deleteProduct(Request $request, EntityManagerInterface $em, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            // Supprimer le produit en fonction du jeton CSRF valide
            $em->remove($product);
            $em->flush();
            $this->addFlash('success', 'Product successfully deleted.');
        }

        // Rediriger vers la liste des produits de l'artisan
        return $this->redirectToRoute('my_products');
    }
}
