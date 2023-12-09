<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Product;
use App\Form\ProductType;
use App\Form\ReviewFormType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
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
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        // Paramètres de pagination et de filtre
        $defaultMaxResults = 20; // Valeur par défaut pour le nombre de produits par page
        $maxResults = $request->query->getInt('maxResults', $defaultMaxResults); // Récupérer la valeur choisie ou utiliser la valeur par défaut
        $sort = $request->query->get('sort', 'newest'); // Tri par défaut
        $category = $request->query->get('category', null); // Aucune catégorie par défaut

        // Récupérer les produits filtrés et paginés
        $totalProducts = $productRepository->countFilteredProducts($category); // Total de produits avec filtre
        $totalPages = ceil($totalProducts / $maxResults);
        $page = max(1, min($request->query->getInt('page', 1), $totalPages));
        $start = ($page - 1) * $maxResults;
        $products = $productRepository->findByFilters($category, $sort, $maxResults, $start);

        // Récupérer les catégories
        $categories = $categoryRepository->findAll();

        // Pour déterminer les pages à afficher
        $maxPagesToShow = 3; // Nombre maximal de pages à afficher
        $pagesToShow = min($maxPagesToShow, $totalPages);
        $halfPagesToShow = floor($pagesToShow / 2);
        $startPage = max(1, $page - $halfPagesToShow);
        $endPage = min($totalPages, $page + $halfPagesToShow);

        if ($endPage - $startPage + 1 < $pagesToShow) {
            if ($startPage === 1) {
                $endPage = min($totalPages, $startPage + $pagesToShow - 1);
            } elseif ($endPage === $totalPages) {
                $startPage = max(1, $endPage - $pagesToShow + 1);
            }
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'totalProducts' => $totalProducts,
            'maxResults' => $maxResults,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'startPage' => $startPage,
            'endPage' => $endPage,
            'sort' => $sort,
            'category' => $category,
            'categories' => $categories
        ]);
    }


    // pour afficher un produit en détail
    #[Route('/product/{id}', name: 'product_show', requirements: ['id' => '\d+'])]
    public function show(int $id, ProductRepository $productRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Trouver le produit par son ID
        $product = $productRepository->find($id);

        // Si le produit n'existe pas, générer une exception
        if (!$product) {
            throw $this->createNotFoundException('The requested product does not exist.');
        }

        // Créer une nouvelle revue
        $review = new Review();
        $review->setProduct($product);
        $review->setUser($this->getUser()); // Assurez-vous que l'utilisateur est connecté

        // Créer le formulaire
        $form = $this->createForm(ReviewFormType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setCreatedAt(new \DateTimeImmutable()); // Définir la date de création
            $entityManager->persist($review);
            $entityManager->flush();

            // Rediriger vers la même page pour afficher la nouvelle revue
            return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
        }

        // Rendre la vue 'product/show.html.twig' avec les détails du produit et le formulaire
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
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
            return $this->redirectToRoute('my_products');
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
