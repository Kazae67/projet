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
use Symfony\Component\HttpFoundation\JsonResponse;

class ReviewController extends AbstractController
{
    #[Route('/product/{id}/review/add', name: 'product_review_add')]
    public function add(Request $request, EntityManagerInterface $entityManager, Product $product): Response
    {
        $review = new Review();
        $review->setProduct($product); // Associer le produit à la revue
        $review->setUser($this->getUser());
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
            throw $this->createAccessDeniedException('You are not allowed to edit this review.');
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
            throw $this->createAccessDeniedException('You are not allowed to delete this review.');
        }

        $entityManager->remove($review);
        $entityManager->flush();

        return $this->redirectToRoute('product_show', ['id' => $review->getProduct()->getId()]);
    }


    // Mise à jour des avis avec AJAX
    #[Route('/review/update/{id}', name: 'review_update')]
    public function update(Request $request, Review $review, EntityManagerInterface $entityManager): JsonResponse
    {
        // Vérification de l'autorisation de l'utilisateur à modifier l'avis
        if ($this->getUser() !== $review->getUser()) {
            // Envoi d'une réponse JSON en cas d'erreur d'autorisation
            return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        // Récupération des données envoyées par l'utilisateur
        $title = $request->request->get('title');
        $rating = $request->request->get('rating');
        $comment = $request->request->get('comment');

        // Mise à jour de l'avis avec les nouvelles informations
        $review->setTitle($title);
        $review->setRating($rating);
        $review->setComment($comment);

        // Sauvegarde des changements dans la base de données
        $entityManager->flush();

        // Envoi d'une réponse JSON confirmant la mise à jour réussie
        return new JsonResponse(['message' => 'Review updated successfully']);
    }
}

