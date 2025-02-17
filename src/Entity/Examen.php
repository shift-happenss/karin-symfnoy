<?php

namespace App\Entity;

use App\Repository\ExamenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExamenRepository::class)]
class Examen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre de l'examen est obligatoire.")]
    #[Assert\Length(
        min: 3,
        minMessage: "Le titre doit contenir au moins {{ limit }} caractères."
    )]
    private ?string $titre = null;

   /* #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "examens")]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "L'examen doit être associé à un utilisateur.")]
    private ?User $utilisateur = null;*/

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[Assert\Length(
        min: 10,
        minMessage: "La description doit contenir au moins {{ limit }} caractères."
    )]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "La note est obligatoire.")]
    #[Assert\Type(type: "float", message: "La note doit être un nombre.")]
    #[Assert\EqualTo(
        value: 0,
        message: "La note doit obligatoirement être 0."
    )]
    private ?float $note = null;

    #[ORM\ManyToOne(inversedBy: 'examens')]
    #[Assert\NotNull(message: "L'examen doit être associé à un cours.")]
    private ?Cours $cours = null;

    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'examen')]
    private Collection $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

   /* public function getUtilisateur(): ?User
     {
        return $this->utilisateur;
     }

     public function setUtilisateur(?User $utilisateur): static
      {
       $this->utilisateur = $utilisateur;
        return $this;
      }
*/

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): static
    {
        $this->cours = $cours;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setExamen($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getExamen() === $this) {
                $question->setExamen(null);
            }
        }

        return $this;
    }
}
