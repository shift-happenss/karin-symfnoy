<?php

namespace App\Entity;

use App\Repository\ReponseSoumiseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseSoumiseRepository::class)]
class ReponseSoumise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $texte = null;

    #[ORM\Column]
    private ?bool $est_correct = null;

    #[ORM\ManyToOne(inversedBy: 'reponse')]
    private ?Question $question = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Reponse $reponse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): static
    {
        $this->texte = $texte;

        return $this;
    }

    public function isEstCorrect(): ?bool
    {
        return $this->est_correct;
    }

    public function setEstCorrect(bool $est_correct): static
    {
        $this->est_correct = $est_correct;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getReponse(): ?Reponse
    {
        return $this->reponse;
    }

    public function setReponse(?Reponse $reponse): static
    {
        $this->reponse = $reponse;

        return $this;
    }
}
