<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le titre ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'formations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Choice(choices: ['Élève', 'Enseignant', 'Parent'], message: 'Choisissez une cible valide (Élève, Enseignant ou Parent).')]
    private ?string $cible = null;
    

    #[ORM\Column(length: 255)]
    private ?string $formateur = null;

    #[ORM\Column(length: 255)]
    #[Assert\Choice(
        choices: ['Non démarrée', 'En cours', 'Terminée'],
        message: "L'état doit être l'un des suivants : Non démarrée, En cours, Terminée."
    )]
    private ?string $etat = 'Non démarrée';


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $urlVideo;  

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $urlImage; 

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $urlFichier; 

    #[ORM\Column(type: 'text', nullable: true)]
    private $contenuTexte; 

    
    public function getContenuTexte(): ?string
    {
        return $this->contenuTexte;
    }

   
    public function setContenuTexte(?string $contenuTexte): self
    {
        $this->contenuTexte = $contenuTexte;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrlVideo(): ?string
    {
        return $this->urlVideo;
    }

    public function setUrlVideo(?string $urlVideo): self
    {
        $this->urlVideo = $urlVideo;

        return $this;
    }

    public function getUrlImage(): ?string
    {
        return $this->urlImage;
    }

    public function setUrlImage(?string $urlImage): self
    {

        $this->urlImage = $urlImage;

        return $this;
    }

    
    public function getUrlFichier(): ?string
    {
        return $this->urlFichier;
    }

    public function setUrlFichier(?string $urlFichier): self
    {
        $this->urlFichier = $urlFichier;

        return $this;
    }


    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

  public function getCategorie(): ?Categorie
{
    return $this->categorie;
}

public function setCategorie(?Categorie $categorie): static
{
    $this->categorie = $categorie;

    return $this;
}

    public function getCible(): ?string
    {
        return $this->cible;
    }

    public function setCible(string $cible): static
    {
        $this->cible = $cible;

        return $this;
    }

    public function getFormateur(): ?string
    {
        return $this->formateur;
    }

    public function setFormateur(string $formateur): static
    {
        $this->formateur = $formateur;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function avancerEtat(): void
    {
        if ($this->etat === 'Non démarrée') {
            $this->etat = 'En cours';
        }
    }



    public function attribuerCertificat(string $userName, EntityManagerInterface $entityManager): Certificat
    {
        $certificat = new Certificat();
        $certificat->setTitle('Certificate for ' . $this->getTitle());
        $certificat->setUserName($userName);
        $certificat->setFormation($this);

        $entityManager->persist($certificat);
        $entityManager->flush();

        return $certificat;
    }



}