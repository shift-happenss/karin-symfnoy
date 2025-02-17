<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Your Name',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'The name field cannot be empty.']),
                    new Assert\Length(['min' => 2, 'max' => 50, 'minMessage' => 'The name must have at least {{ limit }} characters.'])
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Your Family Name',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'The family name field cannot be empty.']),
                    new Assert\Length(['min' => 2, 'max' => 50, 'minMessage' => 'The family name must have at least {{ limit }} characters.'])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Your Email',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Email is required.']),
                    new Assert\Email(['message' => 'Please enter a valid email address.'])
                ]
            ])
            ->add('numtel', TelType::class, [
                'label' => 'Your Phone Number',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Phone number is required.']),
                    new Assert\Regex([
                        'pattern' => '/^\+?[0-9]{9,15}$/',
                        'message' => 'Please enter a valid phone number (9 to 15 digits, optional + at the beginning).'
                    ])
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Your Password',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Password is required.']),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Your password must be at least {{ limit }} characters long.'
                    ])
                ]
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Student' => 'student',
                    'Parent' => 'parent',
                    'Teacher' => 'teacher',
                ],
                'label' => 'Your Role',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Role is required.']),
                    new Assert\Choice([
                        'choices' => ['student', 'parent', 'teacher'],
                        'message' => 'Please select a valid role.'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
