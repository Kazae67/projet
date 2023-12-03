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
        $builder
            ->add('oldPassword', PasswordType::class, [
                'label' => 'Current Password',
                'constraints' => new UserPassword([
                    'message' => 'The current password is incorrect.',
                ]),
                'attr' => ['autocomplete' => 'current-password'],
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The two password fields must match exactly.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'New Password'],
                'second_options' => ['label' => 'Repeat New Password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a new password',
                    ]),
                    new Length([
                        'min' => 12,
                        'max' => 64,
                        'minMessage' => 'Your new password must be at least {{ limit }} characters long',
                        'maxMessage' => 'Your new password must not be longer than {{ limit }} characters',
                    ]),
                    new Regex([
                        'pattern' => '/[A-Z]/',
                        'message' => 'Your new password must contain at least one uppercase letter',
                    ]),
                    new Regex([
                        'pattern' => '/[a-z]/',
                        'message' => 'Your new password must contain at least one lowercase letter',
                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'message' => 'Your new password must contain at least one number',
                    ]),
                    new Regex([
                        'pattern' => '/[!@#$%^&*(),.?":{}|<>]/',
                        'message' => 'Your new password must contain at least one special character (!@#$%^&*(),.?":{}|<>)',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Change password',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChangePasswordModel::class,
        ]);
    }
}
