<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Ressource;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RessourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de la ressource',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez un titre pour la ressource',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la ressource',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Décrivez la ressource en détail',
                    'rows' => 5,
                ],
            ])
            ->add('type', TextType::class, [
                'label' => 'Type de la ressource',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le type de la ressource',
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'Catégorie',
                'choice_label' => 'titre', // Afficher le titre de la catégorie
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('cible', EntityType::class, [
                'class' => User::class,
                'label' => 'Cible (Utilisateurs)',
                'choice_label' => 'name', // Afficher le nom d'utilisateur
                'multiple' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ressource::class,
        ]);
    }
}