<?php

namespace App\Form;

use App\Entity\Adress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;

class AdressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('street', TextType::class, [
                'label' => 'Street',
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
            ])
            ->add('state', TextType::class, [
                'label' => 'State/Province',
                'required' => false,
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Postal Code',
            ])
            ->add('country', CountryType::class, [
                'label' => 'Country',
                'preferred_choices' => ['FR', 'GB', 'DE'],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Delivery' => 'delivery',
                    'Billing' => 'billing',
                ],
                'label' => 'Address Type',
                'attr' => ['class' => 'address-type-select'],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}