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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class, [
            // Ajout de contraintes sur le champ 'name'.
            'constraints' => [
                // Contrainte pour s'assurer que le champ 'name' n'est pas vide.
                new Assert\NotBlank(),
                // Contrainte sur la longueur du champ 'name' : doit être entre 3 et 30 caractères.
                new Assert\Length(['min' => 3, 'max' => 30]),
                // Contrainte Regex pour valider le format du champ 'name'.
                // Ici, on vérifie que le nom ne contient pas de caractères spéciaux comme @{}| pour prévenir les attaques XSS.
                new Assert\Regex([
                    'pattern' => '/^[a-zA-Z0-9\s\/]+(?!\/\/)$/',
                    'message' => 'The title should not contain special characters like @{}|'
                ]),
            ]
        ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 10, 'max' => 5000]),
                    new Assert\Regex([
                        'pattern' => '/^[^<>]*(?!\/\/)[^<>]*$/u',
                        'message' => 'The description should not contain HTML tags.'
                    ]),
                ]
            ])
            ->add('price', MoneyType::class, [
                'currency' => 'EUR',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => 'numeric', 'message' => 'The price must be a number.']),
                    new Assert\GreaterThan(['value' => 0])
                ]
            ])
            ->add('stockQuantity', NumberType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\PositiveOrZero()
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Product Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Assert\Image([
                        'maxSize' => '5M',
                    ]),
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'constraints' => [
                    new Assert\NotNull()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}