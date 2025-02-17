<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Examen;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('texte', TextType::class, [
                'label' => 'Texte de la question',
                'attr' => ['class' => 'form-control']
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de question',
                'choices' => [
                    'Choix Unique' => 'Choix Unique',
                    'Multi-choix' => 'Multi-choix',
                ],
                'expanded' => false, // false = liste déroulante, true = boutons radio
                'multiple' => false, // false = un seul choix possible
                'attr' => ['class' => 'form-select']
            ])
            ->add('examen', EntityType::class, [
                'class' => Examen::class,
                'choice_label' => 'titre', // Assurez-vous que "titre" est bien un champ de l'entité Examen
                'label' => 'Examen associé',
                'attr' => ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-success']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
