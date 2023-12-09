<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class ReviewFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('rating', IntegerType::class, [
                'constraints' => [
                    new Range([
                        'min' => 1,
                        'max' => 5,
                        'minMessage' => 'La note doit être au moins de {{ limit }}',
                        'maxMessage' => 'La note ne peut pas être supérieure à {{ limit }}',
                    ]),
                ],
            ])
            ->add('comment');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
