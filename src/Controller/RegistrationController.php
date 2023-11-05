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

            // Initialiser un tableau pour suivre l'adresse par défaut de chaque type
            $defaultAddresses = [
                'billing' => null,
                'delivery' => null,
            ];

            // Parcourir chaque adresse soumise
            foreach ($user->getAddresses() as $address) {
                // Identifier le type de l'adresse
                $type = $address->getType();

                // Vérifier si l'adresse est marquée comme par défaut et si un type est déjà défini
                if ($address->getIsDefault() && isset($defaultAddresses[$type])) {
                    // Si une adresse par défaut pour ce type est déjà définie, désactiver l'option
                    $address->setIsDefault(false);
                } elseif ($address->getIsDefault()) {
                    // Sinon, définir cette adresse comme par défaut pour le type
                    $defaultAddresses[$type] = $address;
                }
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
