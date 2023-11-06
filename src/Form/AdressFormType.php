<?php

namespace App\Form;

use App\Entity\Adress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AdressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
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
            ])
            ->add('isDefaultBilling', CheckboxType::class, [
                'mapped' => true,
                'required' => false,
                'label' => 'Use as my default billing address',
                'attr' => ['class' => 'default-billing-checkbox'],
            ])
            ->add('isDefaultDelivery', CheckboxType::class, [
                'mapped' => true,
                'required' => false,
                'label' => 'Use as my default delivery address',
                'attr' => ['class' => 'default-delivery-checkbox'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}