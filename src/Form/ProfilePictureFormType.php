<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class ProfilePictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'label' => 'Profile Picture (JPEG/PNG/WebP file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1600K',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPEG, PNG, or WebP image',
                    ])
                ],
            ])
            ->add('upload', SubmitType::class, [
                'label' => 'Upload',
                'attr' => ['class' => 'btn-upload']
            ])
            ->add('delete', SubmitType::class, [
                'label' => 'Delete Profile Picture',
                'attr' => ['class' => 'btn-delete-picture']
            ]);
    }
}
