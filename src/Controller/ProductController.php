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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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

    // action pour afficher un produit en détail
    #[Route('/product/{id}', name: 'product_show', requirements: ['id' => '\d+'])]
    public function show(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('The requested product does not exist.');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/add-to-cart/{id}', name: 'add_to_cart')]
    public function addToCart(int $id, CartService $cartService): Response
    {
        $cartService->add($id);

        // Rediriger vers la page du panier
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart', name: 'cart_index')]
    public function cartIndex(CartService $cartService): Response
    {
        $cart = $cartService->getFullCart();
        $total = $cartService->getTotal();

        return $this->render('cart/index.html.twig', [
            'items' => $cart,
            'total' => $total,
        ]);
    }

    #[IsGranted('ROLE_CRAFTSMAN')]
    #[Route('/product/add', name: 'product_add')]
    public function addProduct(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUser($this->getUser());
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product successfully added.');
            return $this->redirectToRoute('product');
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
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Product successfully updated.');

            return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
