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
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, ReviewRepository $reviewRepository, Request $request): Response
    {
        // Paramètres de pagination et de filtre
        $defaultMaxResults = 20; // Valeur par défaut pour le nombre de produits par page
        $maxResults = $request->query->getInt('maxResults', $defaultMaxResults); // Récupérer la valeur choisie 
        $sort = $request->query->get('sort', 'newest'); 
        $category = $request->query->get('category', null); // Aucune catégorie par défaut
    
        // Traitement des tranches de prix
        $priceMin = $request->query->get('priceMin');
        $priceMax = $request->query->get('priceMax');
    
        // Convertir les chaînes vides en null
        $priceMin = $priceMin !== '' ? $priceMin : null;
        $priceMax = $priceMax !== '' ? $priceMax : null;
    
        // Récupérer les produits filtrés et paginés
        $totalProducts = $productRepository->countFilteredProducts($category, $priceMin, $priceMax); // Total de produits avec filtre
        $totalPages = ceil($totalProducts / $maxResults);
        $page = max(1, min($request->query->getInt('page', 1), $totalPages));
        $start = ($page - 1) * $maxResults;
        $products = $productRepository->findByFilters($category, $sort, $maxResults, $start, $priceMin, $priceMax);
    
        // Calcul de la moyenne des notes et du nombre total de votes pour chaque produit
        foreach ($products as $product) {
            $ratingInfo = $reviewRepository->getAverageRatingPercentForProduct($product);
            $averageRatingPercent = round($ratingInfo['averageRatingPercent'] ?? 0); // Arrondi à l'entier le plus proche
            $product->averageRatingPercent = $averageRatingPercent;
            $product->reviewCount = $ratingInfo['reviewCount'] ?? 0;
        }
    
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
            'categories' => $categories,
            'priceMin' => $priceMin,
            'priceMax' => $priceMax
        ]);
    }
    


    #[Route('/product/{id}', name: 'product_show', requirements: ['id' => '\d+'])]
    public function show(int $id, ProductRepository $productRepository, ReviewRepository $reviewRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = $productRepository->find($id);

        if (!$product || !$product->isActive()) {
            throw $this->createNotFoundException('The requested product does not exist or is not active.');
        }

        $reviews = $reviewRepository->findByProductSortedByDate($product);

        // getAverageRatingPercentForProduct du ReviewRepository
        $ratingInfo = $reviewRepository->getAverageRatingPercentForProduct($product);
        $averageRatingPercent = $ratingInfo['averageRatingPercent'] ?? 0;
        $totalVotes = $ratingInfo['reviewCount'] ?? 0;

        $salesCount = $productRepository->countSalesForProduct($product); // Compte le nombre de produit vendu

        $user = $this->getUser();
        $existingReview = $reviewRepository->findOneBy(['product' => $product, 'user' => $user]);

        $form = $this->createForm(ReviewFormType::class, new Review());

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
            'averageRatingPercent' => $averageRatingPercent,
            'totalVotes' => $totalVotes,
            'salesCount' => $salesCount
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
        // Création d'une nouvelle instance de l'entité Product
        $product = new Product();
        // Création et configuration du formulaire pour le produit
        $form = $this->createForm(ProductType::class, $product);
        // Traitement de la requête HTTP et liaison avec le formulaire
        $form->handleRequest($request);

        // Vérification si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Recherche dans la base de données si un produit avec le même nom existe déjà
            $existingProduct = $productRepository->findOneBy(['name' => $product->getName()]);
            // Gestion du cas où un produit avec le même nom existe déjà
            if ($existingProduct) {
                // Affichage d'un message d'erreur et renvoi du formulaire à l'utilisateur
                $this->addFlash('error', 'A product with this name already exists.');
                return $this->render('product/add.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
    
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                // Extraction du nom de fichier original de l'image uploadée
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // Nettoyage du nom de fichier pour obtenir un nom sûr pour l'URL
                $safeFilename = $slugger->slug($originalFilename);
                // Création d'un nom de fichier unique pour l'image
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
            
                try {
                    // Déplacement de l'image dans le répertoire de stockage
                    $imageFile->move(
                        $this->getParameter('products_directory'),
                        $newFilename
                    );
                    // Mise à jour de l'URL de l'image dans l'entité produit
                    $product->setImageUrl('/uploads/products/'.$newFilename);
                } catch (FileException $e) {
                    // Gestion des erreurs lors de l'upload de l'image
                    $this->addFlash('error', 'Failed to upload image: ' . $e->getMessage());
                    return $this->render('product/add.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
            } else {
                // Utilisation d'une image par défaut si aucune image n'est uploadée
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
    public function myProducts(ProductRepository $productRepository, ReviewRepository $reviewRepository): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            throw $this->createNotFoundException('User not found or not logged in.');
        }
        
        $products = $productRepository->findBy(['user' => $user, 'is_active' => true]);
        $productsWithRatings = [];
    
        foreach ($products as $product) {
            $ratingInfo = $reviewRepository->getAverageRatingPercentForProduct($product);
            $averageRatingPercent = round($ratingInfo['averageRatingPercent'] ?? 0); // Arrondi à l'entier le plus proche
            $salesCount = $productRepository->countSalesForProduct($product); // Compter le nombre total de ventes
    
            $productsWithRatings[] = [
                'product' => $product,
                'averageRatingPercent' => $averageRatingPercent,
                'reviewCount' => $ratingInfo['reviewCount'] ?? 0,
                'salesCount' => $salesCount, // le nombre de ventes
            ];
        }
    
        return $this->render('product/myProducts.html.twig', [
            'productsWithRatings' => $productsWithRatings,
            'products' => $products
        ]);
    }

    #[IsGranted('ROLE_CRAFTSMAN')]
    #[Route('/product/edit/{id}', name: 'product_edit')]
    public function editProduct(Request $request, EntityManagerInterface $em, Product $product, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
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
                    $product->setImageUrl('/uploads/products/'.$newFilename);
                } catch (FileException $e) {
                    // Message d'erreur
                    $this->addFlash('error', 'Failed to upload image: ' . $e->getMessage());
                    return $this->render('product/edit.html.twig', [
                        'form' => $form->createView(),
                        'product' => $product,
                    ]);
                }
            }
    
            $em->flush();
            $this->addFlash('success', 'Product successfully updated.');
    
            return $this->redirectToRoute('my_products', ['id' => $product->getId()]);
        }
    
        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_CRAFTSMAN')]
    #[Route('/product/delete/{id}', name: 'product_delete', methods: ['POST'])]
    public function deleteProduct(Request $request, EntityManagerInterface $em, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            // Désactiver le produit
            $product->setActive(false);
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Product successfully deactivated.');
        }
    
        return $this->redirectToRoute('my_products');
    }
}
