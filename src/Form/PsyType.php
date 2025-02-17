<?php

namespace App\Form;

use App\Entity\Psy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PsyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                
                'label' => 'Nom du psychologue',
            ])
            ->add('numerotel', TelType::class, [
                
                'label' => 'Numéro de téléphone',
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Adresse e-mail',
            ])
            ->add('specialite', TextType::class, [
                
                'label' => 'Spécialité',
            ])
            ->add('datedispo', null, [
                'widget' => 'single_text'
            ])
            ->add('timedispo', null, [
                'widget' => 'single_text'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Psy::class,
        ]);
    }
}
