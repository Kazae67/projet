<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AdressFormType;
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

        $form = $this->createForm(AdressFormType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Address updated successfully.');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/editAdress.html.twig', [
            'addressForm' => $form->createView()
        ]);
    }

    #[Route('/profile/delete-address/{id}', name: 'delete_address', methods: ['POST'])]
    public function deleteAddress(Request $request, EntityManagerInterface $em, Adress $address): Response
    {
        // Vérifier que l'adresse appartient à l'utilisateur connecté
        if ($address->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot delete this address.');
        }

        if ($this->isCsrfTokenValid('delete' . $address->getId(), $request->request->get('_token'))) {
            $em->remove($address);
            $em->flush();

            $this->addFlash('success', 'Address deleted successfully.');
        }

        return $this->redirectToRoute('app_profile');
    }
}
