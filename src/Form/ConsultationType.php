<?php

namespace App\Form;
use App\Entity\Consultation;
use App\Entity\Psy;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Security;
class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date', null, [
            'widget' => 'single_text'
        ])
            ->add('time', null, [
                'widget' => 'single_text'
            ])
            ->add('raison', TextareaType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La raison de la consultation est obligatoire.']),
                    new Assert\Length([
                        'min' => 4,
                        'minMessage' => 'La raison doit contenir au moins {{ limit }} caractères.',
                    ]),
                ],
                'label' => 'Raison de la consultation',
            ])
            ->add('status', null, [
                'data' => 'en attente',  // Valeur affichée par défaut
                'disabled' => true,      // Empêche la modification
                'label' => 'Statut de la consultation',
            ])
            ->add('psy', EntityType::class, [
                'class' => Psy::class,
                'choice_label' => function (Psy $psy) {
                    return $psy->getNom() . ' (' . $psy->getSpecialite() . ')'; // Affiche le nom et spécialité du psy
                },
                'placeholder' => 'Sélectionner un psy',  // Texte par défaut
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Vous devez sélectionner un psy.']),
                ],
                'label' => 'Psychologue',
            ])
           ;
        
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
            
        ]);
       
    }
}
