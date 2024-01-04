<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use App\DTO\ChangePasswordModel;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajout du champ pour l'ancien mot de passe avec des contraintes pour vérifier le mot de passe actuel
        $builder->add('oldPassword', PasswordType::class, [
            'label' => 'Current Password', // Étiquette du champ
            'constraints' => new UserPassword([
                'message' => 'The current password is incorrect.', // Message en cas de mot de passe incorrect
            ]),
            'attr' => ['autocomplete' => 'current-password'], // Attributs HTML pour améliorer l'expérience utilisateur
        ]);
    
        // Ajout du champ pour le nouveau mot de passe, demandant à l'utilisateur de le saisir deux fois pour vérification
        $builder->add('newPassword', RepeatedType::class, [
            'type' => PasswordType::class, // Spécifie le type des champs répétés
            'invalid_message' => 'The two password fields must match exactly.', // Message d'erreur si les mots de passe ne correspondent pas
            'options' => ['attr' => ['class' => 'password-field']], // Attributs HTML pour le champ
            'required' => true, // Rend le champ obligatoire
            'first_options' => ['label' => 'New Password'], // Options pour le premier champ de mot de passe
            'second_options' => ['label' => 'Repeat New Password'], // Options pour le second champ de mot de passe
            'constraints' => [ // Ensemble de contraintes pour valider le nouveau mot de passe
                new NotBlank([
                    'message' => 'Please enter a new password', // Contrainte pour un champ non vide
                ]),
                new Length([
                    'min' => 12, // Longueur minimale
                    'max' => 64, // Longueur maximale
                    'minMessage' => 'Your new password must be at least {{ limit }} characters long',
                    'maxMessage' => 'Your new password must not be longer than {{ limit }} characters',
                ]),
                new Regex([
                    'pattern' => '/[A-Z]/', // Contrainte pour au moins une lettre majuscule
                    'message' => 'Your new password must contain at least one uppercase letter',
                ]),
                new Regex([
                    'pattern' => '/[a-z]/', // Contrainte pour au moins une lettre minuscule
                    'message' => 'Your new password must contain at least one lowercase letter',
                ]),
                new Regex([
                    'pattern' => '/\d/', // Contrainte pour au moins un chiffre
                    'message' => 'Your new password must contain at least one number',
                ]),
                new Regex([
                    'pattern' => '/[!@#$%^&*(),.?":{}|<>]/', // Contrainte pour au moins un caractère spécial
                    'message' => 'Your new password must contain at least one special character (!@#$%^&*(),.?":{}|<>)',
                ]),
            ],
        ])
        // Ajout d'un bouton de soumission pour le formulaire
        ->add('submit', SubmitType::class, [
            'label' => 'Change password', // Étiquette du bouton
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChangePasswordModel::class,
        ]);
    }
}
