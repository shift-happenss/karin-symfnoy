<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ConsultationRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $time = null;


    #[Assert\NotBlank(message: "La raison est obligatoire.")]
    #[Assert\Length(min: 4,max: 255,minMessage: "La raison doit contenir au moins {{ limit }} caractères.",maxMessage: "La raison ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(pattern: "/^[a-zA-ZÀ-ÿ0-9\s\-\.]+$/u",message: "La raison ne peut contenir que des lettres, chiffres, espaces ou ponctuation simple.")]
    #[ORM\Column(length: 255)]
    private ?string $raison = null;

    #[Assert\NotBlank(message: "Le statut est obligatoire.")]
    #[ORM\Column(length: 255)]
    private ?string $status = 'en attente';


    #[Assert\NotNull(message: "Un psy doit être associé à cette consultation.")]
    #[ORM\ManyToOne(inversedBy: 'consultations')]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Psy $psy = null;

    
   

    #[Assert\Callback]
    public function validateDateAndTime(ExecutionContextInterface $context): void
    {
        if ($this->date && $this->time) {
            $datetime = \DateTime::createFromFormat('Y-m-d H:i', $this->date->format('Y-m-d') . ' ' . $this->time->format('H:i'));
            if ($datetime < new \DateTime()) {
                $context->buildViolation('La date et l’heure doivent être dans le futur.')
                    ->atPath('time')
                    ->addViolation();
            }
        }
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): static
    {
        $this->time = $time;

        return $this;
    }
    

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(string $raison): static
    {
        $this->raison = $raison;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPsy(): ?Psy
    {
        return $this->psy;
    }

    public function setPsy(?Psy $psy): static
    {
        $this->psy = $psy;

        return $this;
    }
    
    
}
