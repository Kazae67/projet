<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderConfirmationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champs pour l'adresse
            ->add('street', TextType::class, [
                'label' => 'Street',
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
                'required' => false
            ])
            ->add('state', TextType::class, [
                'label' => 'State/Province',
                'required' => false
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Postal Code',
                'required' => false
            ])
            ->add('country', CountryType::class, [
                'label' => 'Country',
                'required' => false,
                'preferred_choices' => ['FR', 'GB', 'DE']
            ])
            ->add('type', ChoiceType::class, [
                'choices' => ['Delivery' => 'delivery', 'Billing' => 'billing'],
                'label' => 'Address Type',
                'required' => false
            ])
            // Champs pour prénom et nom
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'required' => true
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'required' => true
            ])
            // Champs pour sélectionner l'adresse
            ->add('selectedAddress', ChoiceType::class, [
                'label' => 'Select Address',
                'choices' => [
                    'Use default billing address' => 'billing_default',
                    'Use default delivery address' => 'delivery_default',
                    'Use a new address' => 'new_address',
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => false
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
