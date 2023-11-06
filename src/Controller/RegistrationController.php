<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash le mot de passe de l'utilisateur
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $defaultBillingSet = false;
            $defaultDeliverySet = false;

            // Parcourir chaque adresse soumise
            foreach ($user->getAddresses() as $address) {
                // Si l'utilisateur a sélectionné cette adresse comme facturation par défaut
                if (!$defaultBillingSet && $address->getIsDefaultBilling()) {
                    $defaultBillingSet = true;
                } else {
                    // Si une autre adresse a déjà été définie comme facturation par défaut
                    $address->setIsDefaultBilling(false);
                }

                // Si l'utilisateur a sélectionné cette adresse comme livraison par défaut
                if (!$defaultDeliverySet && $address->getIsDefaultDelivery()) {
                    $defaultDeliverySet = true;
                } else {
                    // Si une autre adresse a déjà été définie comme livraison par défaut
                    $address->setIsDefaultDelivery(false);
                }
            }

            // S'assurer qu'au moins une adresse est définie comme par défaut si l'utilisateur en a saisi
            if (!$defaultBillingSet && !$defaultDeliverySet && !$user->getAddresses()->isEmpty()) {
                $firstAddress = $user->getAddresses()->first();
                $firstAddress->setIsDefaultBilling(true);
                $firstAddress->setIsDefaultDelivery(true);
            }

            // Persister seulement l'utilisateur. Les adresses seront persistées en cascade
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect sur login après l'enregistrement
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
