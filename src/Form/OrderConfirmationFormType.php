<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Security;

class OrderConfirmationFormType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $addressChoices = [];

        if (null !== $user->getDefaultDeliveryAddress()) {
            $addressChoices['Use default delivery address'] = 'delivery_default';
        }

        if (null !== $user->getDefaultBillingAddress()) {
            $addressChoices['Use default billing address'] = 'billing_default';
        }

        $addressChoices['Use a new address'] = 'new_address';

        $builder
            // Validation de longueur : Pour les champs street, city, state, et postalCode, j'ai ajouté des contraintes de longueur pour éviter des entrées trop longues.
            // Champs pour l'adresse
            ->add('street', TextType::class, [
                'label' => 'Street',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 100]),
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 50]),
                ]
            ])
            ->add('state', TextType::class, [
                'label' => 'State/Province',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 50]),
                ]
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Postal Code',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 20]),
                ]
            ])
            // Contrainte de pays : Pour le champ country, une contrainte Country est ajoutée pour s'assurer que le pays saisi est valide.
            ->add('country', CountryType::class, [
                'label' => 'Country',
                'required' => false,
                'preferred_choices' => ['FR', 'GB', 'DE']
            ])
            // Contrainte de choix : Pour type et selectedAddress, une contrainte Choice s'assure que la sélection de l'utilisateur correspond aux options disponibles.
            ->add('type', ChoiceType::class, [
                'choices' => ['Delivery' => 'delivery', 'Billing' => 'billing'],
                'label' => 'Address Type',
                'required' => false,
                'placeholder' => false,
                'constraints' => [
                    new Assert\Choice(['delivery', 'billing']),
                ]
            ])
            // Validation des noms : Pour firstName et lastName, des contraintes NotBlank et Length garantissent que les noms sont bien fournis et de longueur appropriée.
            // Champs pour prénom et nom
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your first name',
                    ]),
                    new Assert\Length(['max' => 50]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z\s-]+$/',
                        'message' => 'The first name should only contain letters, spaces, and hyphens.'
                    ])
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your last name',
                    ]),
                    new Assert\Length(['max' => 50]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z\s-]+$/',
                        'message' => 'The last name should only contain letters, spaces, and hyphens.'
                    ])
                ]
            ])

            // Champs pour sélectionner l'adresse
            ->add('selectedAddress', ChoiceType::class, [
                'label' => 'Select Address',
                'choices' => $addressChoices,
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new Assert\Choice(['choices' => array_values($addressChoices)]),
                ],
                'choice_attr' => function($choice, $key, $value) {
                    if ($value === 'new_address') {
                        return ['class' => 'new-address-radio'];
                    }
                    return [];
                },
            ])
            // Boutons de soumission
            ->add('saveAddress', SubmitType::class, [
                'label' => 'Save Address',
                'attr' => ['class' => 'btn btn-save'],
                'validation_groups' => false
            ])
            ->add('confirmOrder', SubmitType::class, [
                'label' => 'Confirm Order',
                'attr' => ['class' => 'btn btn-confirm']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
