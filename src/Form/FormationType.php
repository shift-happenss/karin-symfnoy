<?php


namespace App\Form;

use App\Entity\Formation;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'le titre de formation est obligatoire.'])]
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name', 
                'label' => 'Catégorie',
                'placeholder' => 'Sélectionnez une catégorie', 
            ])
            ->add('formateur', TextType::class, [
                'label' => 'formateur',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'le nom de formateur est obligatoire.'])]
            ])
            
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'le description est obligatoire.'])]
            ])

            ->add('contenuTexte', TextareaType::class, [
                'label' => 'Contenu Texte',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'le contenu est obligatoire.'])]
            ])
            ->add('urlVideo', UrlType::class, [
                'label' => 'URL Vidéo',
                'required' => false,
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image de la formation',
                'mapped' => false, // Ce champ n'est pas lié à l'entité directement
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPG, PNG)',
                    ])
                ],
            ])
            ->add('file', FileType::class, [
                'label' => 'Fichier (PDF, DOC, etc.)',
                'mapped' => false, // Ce champ n'est pas lié directement à l'entité
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
                        'mimeTypesMessage' => 'Veuillez uploader un fichier PDF ou Word valide.',
                    ])
                ],
            ])
            
            ->add('cible', ChoiceType::class, [
                'choices' => [
                    'Élève' => 'Élève',
                    'Enseignant' => 'Enseignant',
                    'Parent' => 'Parent',
                ],
                'placeholder' => 'Choisissez la cible',
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class, // Associe ce formulaire à l'entité Formation
        ]);
    }
}