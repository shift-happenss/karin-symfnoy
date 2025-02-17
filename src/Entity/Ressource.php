<?php

namespace App\Entity;

use App\Repository\RessourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RessourceRepository::class)]
class Ressource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le titre est obligatoire.")]
    #[Assert\Length(min: 4,minMessage: "Le titre doit contenir au moins {{ limit }} caractères.")]
    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[Assert\Length(min: 4,minMessage: "La description doit contenir au moins {{ limit }} caractères.")]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[Assert\NotBlank(message: "Le type est obligatoire.")]
    #[Assert\Length(min: 4,minMessage: "Le type doit contenir au moins {{ limit }} caractères.")]
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $cible;

    public function __construct()
    {
        $this->cible = new ArrayCollection();
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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getCible(): Collection
    {
        return $this->cible;
    }

    public function addCible(User $cible): static
    {
        if (!$this->cible->contains($cible)) {
            $this->cible->add($cible);
        }

        return $this;
    }

    public function removeCible(User $cible): static
    {
        $this->cible->removeElement($cible);

        return $this;
    }
}
