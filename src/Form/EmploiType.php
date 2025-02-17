<?php
namespace App\Form;

use App\Entity\Emploi;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmploiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('proprietaire', EntityType::class, [
                'label' => 'Propriétaire',
                'class' => User::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => true, // Rendre le champ en lecture seule si nécessaire
                ],
            ])
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez un titre pour l\'emploi',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Décrivez l\'emploi en détail',
                    'rows' => 5, // Ajuster la hauteur du champ
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Créer',
                'attr' => [
                    'class' => 'btn btn-primary mt-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emploi::class,
        ]);
    }
}
