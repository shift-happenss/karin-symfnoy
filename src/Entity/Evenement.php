<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le type d'événement est obligatoire.")]
    #[Assert\Length(min: 3,minMessage: "Le type d'événement doit contenir au moins {{ limit }} caractères.")]
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[Assert\NotNull(message: "La date de début est obligatoire.")]
    #[Assert\Type(type: \DateTimeInterface::class,message: "La date de début doit être une date valide.")]
    #[Assert\GreaterThanOrEqual("today",message: "La date de début ne peut pas être dans le passé.")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[Assert\NotNull(message: "La date de fin est obligatoire.")]
    #[Assert\Type(type: \DateTimeInterface::class,message: "La date de fin doit être une date valide.")]
    #[Assert\GreaterThan(propertyPath: "dateDebut",message: "La date de fin doit être postérieure à la date de début.")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;
    
    
    #[Assert\NotBlank(message: "Le contenu est obligatoire.")]
    #[Assert\Length(min: 4,minMessage: "Le contenu doit contenir au moins {{ limit }} caractères.")]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[Assert\NotBlank(message: "Le lieu est obligatoire.")]
    #[Assert\Length(min: 3,minMessage: "Le lieu doit contenir au moins {{ limit }} caractères.")]
    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[Assert\NotNull(message: "L'événement doit être lié à un emploi.")]
    #[ORM\ManyToOne(inversedBy: 'evenements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Emploi $emploi = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getEmploi(): ?Emploi
    {
        return $this->emploi;
    }

    public function setEmploi(?Emploi $emploi): static
    {
        $this->emploi = $emploi;

        return $this;
    }
}
