<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Product;
use App\Form\ProductType;
use App\Form\ReviewFormType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\ReviewRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

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


    #[Route('/product/{id}', name: 'product_show', requirements: ['id' => '\d+'])]
    public function show(int $id, ProductRepository $productRepository, ReviewRepository $reviewRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = $productRepository->find($id);
    
        if (!$product) {
            throw $this->createNotFoundException('The requested product does not exist.');
        }
    
        // Récupérer les revues triées du plus récent au plus ancien
        $reviews = $reviewRepository->findByProductSortedByDate($product);
    
        $user = $this->getUser();
        $existingReview = $reviewRepository->findOneBy(['product' => $product, 'user' => $user]);
    
        $form = $this->createForm(ReviewFormType::class, new Review());
    
        // Afficher le formulaire seulement si l'utilisateur n'a pas déjà laissé de revue
        // et si l'utilisateur n'est pas le propriétaire du produit
        if (!$existingReview && $user !== $product->getUser()) {
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $review = $form->getData();
                $review->setProduct($product);
                $review->setUser($user);
                $review->setCreatedAt(new \DateTimeImmutable());
                $entityManager->persist($review);
                $entityManager->flush();
    
                return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
            }
        }
    
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'reviews' => $reviews,
            'form' => $form->createView(),
            'existingReview' => $existingReview,
            'canReview' => !$existingReview && $user !== $product->getUser(),
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
    public function addProduct(Request $request, EntityManagerInterface $em, ProductRepository $productRepository, SluggerInterface $slugger): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification de l'existence d'un produit similaire
            $existingProduct = $productRepository->findOneBy(['name' => $product->getName()]);
            if ($existingProduct) {
                $this->addFlash('error', 'A product with this name already exists.');
                return $this->render('product/add.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // Traitement de l'upload d'image
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('products_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gestion de l'erreur d'upload
                }

                $product->setImageUrl('/uploads/products/'.$newFilename);
            } else {
                $product->setImageUrl('/images/default-image.jpg');
            }

            $product->setUser($this->getUser());
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product successfully added.');
            return $this->redirectToRoute('my_products');
        }

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
