<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
            $builder
            // Ajout d'un champ 'username' au formulaire.
            ->add('username', null, [
                'required' => true, // Le champ est obligatoire.
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a username', // Contrainte pour s'assurer que le champ n'est pas vide.
                    ]),
                    new Length([
                        'min' => 4,
                        'max' => 20,
                        'minMessage' => 'Your username must be at least {{ limit }} characters long', // Contrainte sur la longueur du nom d'utilisateur.
                        'maxMessage' => 'Your username cannot be longer than {{ limit }} characters',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9]+$/', // Contrainte pour s'assurer que le nom d'utilisateur ne contient que des lettres et des chiffres.
                        'message' => 'Your username can only contain letters and numbers',
                    ]),
                ]
            ])
            // Ajout d'un champ 'email' de type EmailType au formulaire.
            ->add('email', EmailType::class, [
                'required' => true, // Le champ est obligatoire.
                'constraints' => [
                    new Email([
                        'message' => 'Please enter a valid email address.', // Contrainte pour valider le format de l'adresse e-mail.
                    ]),
                    new NotBlank([
                        'message' => 'Please enter an email address.', // Contrainte pour s'assurer que le champ n'est pas vide.
                    ]),
                ]
            ])
            // Ajout d'une section pour la saisie du mot de passe dans le formulaire.
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class, // Spécifie le type de champ comme un champ de mot de passe.
                'first_options' => ['label' => 'Password'], // Options pour le premier champ de saisie du mot de passe.
                'second_options' => ['label' => 'Repeat Password'], // Options pour le champ de confirmation du mot de passe.
                'mapped' => false, // Indique que ce champ ne doit pas être mappé directement à une propriété de l'entité.
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password', // Contrainte pour s'assurer que le champ n'est pas vide.
                    ]),
                    new Length([
                        'min' => 12,
                        'max' => 64,
                        'minMessage' => 'Your password must be at least {{ limit }} characters long', // Contrainte sur la longueur du mot de passe.
                        'maxMessage' => 'Your password must not be longer than {{ limit }} characters',
                    ]),
                    // Plusieurs contraintes Regex pour valider la complexité du mot de passe.
                    new Regex([
                        'pattern' => '/[A-Z]/', // Au moins une lettre majuscule.
                        'message' => 'Your password must contain at least one uppercase letter',
                    ]),
                    new Regex([
                        'pattern' => '/[a-z]/', // Au moins une lettre minuscule.
                        'message' => 'Your password must contain at least one lowercase letter',
                    ]),
                    new Regex([
                        'pattern' => '/\d/', // Au moins un chiffre.
                        'message' => 'Your password must contain at least one number',
                    ]),
                    new Regex([
                        'pattern' => '/[!@#$%^&*(),.?":{}|<>]/', // Au moins un caractère spécial.
                        'message' => 'Your password must contain at least one special character (!@#$%^&*(),.?":{}|<>)',
                    ]),
                ]
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Craftsman' => 'craftsman',
                    'Customer' => 'customer',
                ],
                'label' => 'Role',
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => new IsTrue([
                    'message' => 'You must agree to the terms.',
                ]),
                'label' => 'I agree to the Terms and Conditions',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
