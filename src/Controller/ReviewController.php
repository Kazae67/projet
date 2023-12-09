<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Product;
use App\Form\ReviewFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    #[Route('/product/{id}/review/add', name: 'product_review_add')]
    public function add(Request $request, EntityManagerInterface $entityManager, Product $product): Response
    {
        $review = new Review();
        $review->setProduct($product); // Associer le produit à la revue
        $review->setUser($this->getUser()); // Assurez-vous que l'utilisateur est connecté
        $review->setCreatedAt(new \DateTimeImmutable());

        // Créer et traiter le formulaire
        $form = $this->createForm(ReviewFormType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($review);
            $entityManager->flush();

            // Rediriger vers la page du produit avec un message de succès
            return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
        }

        // Rendre le formulaire dans le template
        return $this->render('review/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/review/edit/{id}', name: 'review_edit')]
    public function edit(Request $request, Review $review, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si l'utilisateur est le propriétaire de la revue
        if ($this->getUser() !== $review->getUser()) {
            throw $this->createAccessDeniedException('Vous nêtes pas autorisé à éditer cette revue.');
        }
    
        $form = $this->createForm(ReviewFormType::class, $review);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('product_show', ['id' => $review->getProduct()->getId()]);
        }
    
        return $this->render('review/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/review/delete/{id}', name: 'review_delete')]
public function delete(Review $review, EntityManagerInterface $entityManager): Response
{
    if ($this->getUser() !== $review->getUser()) {
        throw $this->createAccessDeniedException('Vous nêtes pas autorisé à supprimer cette revue.');
    }

    $entityManager->remove($review);
    $entityManager->flush();

    return $this->redirectToRoute('product_show', ['id' => $review->getProduct()->getId()]);
}
}

