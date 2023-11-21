<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Champ 'name' avec contraintes pour s'assurer que le nom est non vide et de longueur appropriée
            //Limiter la longueur pour éviter des entrées excessivement longues, ce qui peut être une source de failles XSS.
            ->add('name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 3, 'max' => 30]), // Limite de 30 caractères
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z0-9\s\/]+(?!\/\/)$/',
                        'message' => 'The title should not contain special characters like @{}|'
                    ]),
                ]
            ])
            // Champ 'description' avec contrainte de longueur pour éviter des entrées trop longues. 
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 10, 'max' => 5000]),
                    new Assert\Regex([
                        'pattern' => '/^[^<>]*(?!\/\/)[^<>]*$/u', // Interdit les caractères < et > pour éviter les balises HTML et empêche les doubles slash pour les liens
                        'message' => 'The description should not contain HTML tags.'
                    ]),
                ]
            ])
            // Champ 'price' pour s'assurer que le prix est un nombre positif
            ->add('price', MoneyType::class, [
                'currency' => 'EUR',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => 'numeric', 'message' => 'The price must be a number.']),
                    new Assert\GreaterThan(['value' => 0])
                ]
            ])
            // Champ 'stockQuantity' pour s'assurer que la quantité est positive ou zéro
            ->add('stockQuantity', NumberType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\PositiveOrZero()
                ]
            ])
            // Champ 'imageUrl' avec validation d'URL
            ->add('imageUrl', UrlType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Url()
                ]
            ])
            // Champ 'category' pour sélectionner une catégorie existante
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'constraints' => [
                    new Assert\NotNull()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
