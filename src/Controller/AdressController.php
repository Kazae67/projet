<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AdressFormType;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdressController extends AbstractController
{
    #[Route('/profile/edit-address/{id}', name: 'edit_address')]
    public function editAddress(Request $request, EntityManagerInterface $em, Adress $address): Response
    {
        // Vérifier que l'adresse appartient à l'utilisateur connecté
        if ($address->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot edit this address.');
        }

        // Créer un formulaire en utilisant AdressFormType et lier les données de l'adresse
        $form = $this->createForm(AdressFormType::class, $address);
        $form->handleRequest($request);

        // Vérifier si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour l'adresse dans la base de données
            $em->flush();
            // Ajouter un message flash pour indiquer la mise à jour réussie
            $this->addFlash('success', 'Address updated successfully.');
            // Rediriger l'utilisateur vers la route 'app_profile'
            return $this->redirectToRoute('app_profile');
        }

        // Afficher le formulaire de modification d'adresse
        return $this->render('profile/editAdress.html.twig', [
            'addressForm' => $form->createView()
        ]);
    }

    #[Route('/profile/delete-address/{id}', name: 'delete_address', methods: ['POST'])]
    public function deleteAddress(Request $request, EntityManagerInterface $em, Adress $address): Response
    {
        $user = $this->getUser();
        if ($address->getUser() !== $user) {
            throw $this->createAccessDeniedException('You cannot delete this address.');
        }

        if ($this->isCsrfTokenValid('delete' . $address->getId(), $request->request->get('_token'))) {
            // Vérifier et mettre à jour les adresses par défaut dans 'user'
            if ($user->getDefaultBillingAddress() === $address) {
                $user->setDefaultBillingAddress(null);
            }
            if ($user->getDefaultDeliveryAddress() === $address) {
                $user->setDefaultDeliveryAddress(null);
            }

            // Dissocier l'adresse des commandes existantes
            $orders = $em->getRepository(Order::class)->findBy(['address' => $address]);
            foreach ($orders as $order) {
                $order->setAddress(null);
                $em->persist($order);
            }

            // Supprimer l'adresse de la base de données
            $em->remove($address);
            $em->flush();

            $this->addFlash('success', 'Address deleted successfully.');
        } else {
            dd('CSRF check failed'); // Vérifier si la validation CSRF échoue
        }

        return $this->redirectToRoute('app_profile');
    }
}
