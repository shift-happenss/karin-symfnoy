<?php

// src/Entity/Certificat.php

namespace App\Entity;

use App\Repository\CertificatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CertificatRepository::class)]
class Certificat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    private $userName; 

    #[ORM\ManyToOne(targetEntity: Formation::class, inversedBy: 'certificats')]
    #[ORM\JoinColumn(nullable: false)]
    private $formation;

    #[ORM\Column(type: 'datetime')]
    private $dateIssued;

    public function __construct()
    {
        $this->dateIssued = new \DateTime();
    }

    // Getters and setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;
        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;
        return $this;
    }

    public function getDateIssued(): ?\DateTimeInterface
    {
        return $this->dateIssued;
    }

    public function setDateIssued(\DateTimeInterface $dateIssued): self
    {
        $this->dateIssued = $dateIssued;
        return $this;
    }
}