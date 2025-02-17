<?php

namespace App\Form;

use App\Entity\Reponse;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', EntityType::class, [
                'class' => Question::class,
                'choice_label' => 'texte', // Affiche le texte de la question
                'placeholder' => 'Sélectionnez une question',
            ])
            ->add('estCorrecte', ChoiceType::class, [
                'choices' => [
                    'Réponse A' => 'A',
                    'Réponse B' => 'B',
                    'Réponse C' => 'C',
                    'Réponse D' => 'D',
                    'Réponse A,B' => 'A,B',
                    'Réponse A,C' => 'A,C',
                    'Réponse A,D' => 'A,D',
                    'Réponse B,C' => 'B,C',
                    'Réponse B,D' => 'B,D',
                    'Réponse C,D' => 'C,D',
                    'Réponse A,B,C' => 'A,B,C',
                    'Réponse A,B,D' => 'A,B,D',
                    'Réponse A,C,D' => 'A,C,D',
                    'Réponse B,C,D' => 'B,C,D',
                    'Réponse A,B,C,D' => 'A,B,C,D',


                ],
                'label' => 'Réponse correcte',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
        ]);
    }
}
