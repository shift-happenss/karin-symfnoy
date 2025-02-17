<?php

namespace App\Entity;

use App\Entity\Consultation;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PsyRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


#[ORM\Entity(repositoryClass: PsyRepository::class)]
class Psy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    #[Assert\Length(min: 4,minMessage: "Le nom doit contenir au moins {{ limit }} caractères.")]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire.")]
    #[Assert\Regex(pattern: "/^\d{8}$/",message: "Le numéro de téléphone doit contenir exactement 8 chiffres.")]
    #[ORM\Column]
    private ?int $numerotel = null;


    #[Assert\NotBlank(message: "L'adresse e-mail est obligatoire.")]
    #[Assert\Email(message: "L'adresse e-mail n'est pas valide.")]
    #[ORM\Column(length: 255)]
    private ?string $mail = null;


    #[Assert\NotBlank(message: "La spécialité est obligatoire.")]
    #[Assert\Length(min: 4,max: 255,minMessage: "La spécialité doit contenir au moins {{ limit }} caractères.",maxMessage: "La spécialité ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(pattern: "/^[a-zA-ZÀ-ÿ\s\-\.]+$/u",message: "La spécialité ne peut contenir que des lettres, espaces ou ponctuation simple.")]
    #[ORM\Column(length: 255)]
    private ?string $specialite = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datedispo = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $timedispo = null;


    /**
     * @var Collection<int, Consultation>
     */
    #[ORM\OneToMany(targetEntity: Consultation::class, mappedBy: 'psy')]
    private Collection $consultations;

    public function __construct()
    {
        $this->consultations = new ArrayCollection();
    }

    #[Assert\Callback]
    public function validateAvailability(ExecutionContextInterface $context): void
    {
        if ($this->datedispo && $this->timedispo) {
            $datetime = \DateTime::createFromFormat('Y-m-d H:i', $this->datedispo->format('Y-m-d') . ' ' . $this->timedispo->format('H:i'));
            if ($datetime < new \DateTime()) {
                $context->buildViolation('La date et l’heure de disponibilité doivent être dans le futur.')
                    ->atPath('timedispo')
                    ->addViolation();
            }
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNumerotel(): ?int
    {
        return $this->numerotel;
    }

    public function setNumerotel(int $numerotel): static
    {
        $this->numerotel = $numerotel;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getDatedispo(): ?\DateTimeInterface
    {
        return $this->datedispo;
    }

    public function setDatedispo(\DateTimeInterface $datedispo): static
    {
        $this->datedispo = $datedispo;

        return $this;
    }

    public function getTimedispo(): ?\DateTimeInterface
    {
        return $this->timedispo;
    }

    public function setTimedispo(\DateTimeInterface $timedispo): static
    {
        $this->timedispo = $timedispo;

        return $this;
    }

    /**
     * @return Collection<int, Consultation>
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    public function addConsultation(Consultation $consultation): static
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations->add($consultation);
            $consultation->setPsy($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): static
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getPsy() === $this) {
                $consultation->setPsy(null);
            }
        }

        return $this;
    }
}
