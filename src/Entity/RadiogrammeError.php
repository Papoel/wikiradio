<?php

namespace App\Entity;

use App\Repository\RadiogrammeErrorRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RadiogrammeErrorRepository::class)]
#[ORM\Table(name: 'radiogrammes_errors')]
#[ORM\HasLifecycleCallbacks]
class RadiogrammeError
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $tranche = null;

    #[ORM\Column(length: 3)]
    private ?string $systeme = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ligne = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $bigramme = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $iso = null;

    #[ORM\Column(length: 20)]
    private ?string $repere = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $visite = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $traversee = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $armoire = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $etagere = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $boite = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $oi = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rf = null;

    #[ORM\Column]
    private ?bool $isIps = false;

    #[ORM\Column]
    private ?bool $isQs = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cpp = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $csp = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commande = null;

    #[ORM\Column(length: 255)]
    private ?string $titulaire = '';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $uniqueValue = null;

    #[ORM\Column(length: 255)]
    private string $errorType = '';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $errorMessage = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column]
    private bool $isResolved = false;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTranche(): ?int
    {
        return $this->tranche;
    }

    public function setTranche(int $tranche): static
    {
        $this->tranche = $tranche;

        return $this;
    }

    public function getSysteme(): ?string
    {
        return $this->systeme;
    }

    public function setSysteme(string $systeme): static
    {
        $this->systeme = $systeme;

        return $this;
    }

    public function getLigne(): ?string
    {
        return $this->ligne;
    }

    public function setLigne(?string $ligne): static
    {
        $this->ligne = $ligne;

        return $this;
    }

    public function getBigramme(): ?string
    {
        return $this->bigramme;
    }

    public function setBigramme(?string $bigramme): static
    {
        $this->bigramme = $bigramme;

        return $this;
    }

    public function getIso(): ?string
    {
        return $this->iso;
    }

    public function setIso(?string $iso): static
    {
        $this->iso = $iso;

        return $this;
    }

    public function getRepere(): ?string
    {
        return $this->repere;
    }

    public function setRepere(?string $repere): static
    {
        $this->repere = $repere;

        return $this;
    }

    public function getVisite(): ?string
    {
        return $this->visite;
    }

    public function setVisite(?string $visite): static
    {
        $this->visite = $visite;

        return $this;
    }

    public function getTraversee(): ?string
    {
        return $this->traversee;
    }

    public function setTraversee(?string $traversee): static
    {
        $this->traversee = $traversee;

        return $this;
    }

    public function getArmoire(): ?string
    {
        return $this->armoire;
    }

    public function setArmoire(?string $armoire): static
    {
        $this->armoire = $armoire;

        return $this;
    }

    public function getEtagere(): ?string
    {
        return $this->etagere;
    }

    public function setEtagere(?string $etagere): static
    {
        $this->etagere = $etagere;

        return $this;
    }

    public function getBoite(): ?string
    {
        return $this->boite;
    }

    public function setBoite(?string $boite): static
    {
        $this->boite = $boite;

        return $this;
    }

    public function getOi(): ?string
    {
        return $this->oi;
    }

    public function setOi(?string $oi): static
    {
        $this->oi = $oi;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getRf(): ?string
    {
        return $this->rf;
    }

    public function setRf(?string $rf): static
    {
        $this->rf = $rf;

        return $this;
    }

    public function isIps(): ?bool
    {
        return $this->isIps;
    }

    public function setIsIps(bool $isIps): static
    {
        $this->isIps = $isIps;

        return $this;
    }

    public function isQs(): ?bool
    {
        return $this->isQs;
    }

    public function setIsQs(bool $isQs): static
    {
        $this->isQs = $isQs;

        return $this;
    }

    public function getCpp(): ?string
    {
        return $this->cpp;
    }

    public function setCpp(?string $cpp): static
    {
        $this->cpp = $cpp;

        return $this;
    }

    public function getCsp(): ?string
    {
        return $this->csp;
    }

    public function setCsp(?string $csp): static
    {
        $this->csp = $csp;

        return $this;
    }

    public function getCommande(): ?string
    {
        return $this->commande;
    }

    public function setCommande(?string $commande): static
    {
        $this->commande = $commande;

        return $this;
    }

    public function getTitulaire(): ?string
    {
        return $this->titulaire;
    }

    public function setTitulaire(string $titulaire): static
    {
        $this->titulaire = $titulaire;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(?string $observation): static
    {
        $this->observation = $observation;

        return $this;
    }

    public function getUniqueValue(): ?string
    {
        return $this->uniqueValue;
    }

    public function setUniqueValue(?string $uniqueValue): static
    {
        $this->uniqueValue = $uniqueValue;

        return $this;
    }

    public function getErrorType(): string
    {
        return $this->errorType;
    }

    public function setErrorType(string $errorType): static
    {
        $this->errorType = $errorType;

        return $this;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(?string $errorMessage): static
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isResolved(): bool
    {
        return $this->isResolved;
    }

    public function setIsResolved(bool $isResolved): static
    {
        $this->isResolved = $isResolved;

        return $this;
    }

    /**
     * Génère automatiquement la valeur unique avant la persistance
     */
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function generateUniqueValue(): void
    {
        // Génère la valeur unique à partir des champs qui identifient la soudure
        // Utiliser le même format que dans Radiogramme::getUniqueValue()
        $year = $this->date ? $this->date->format('Y') : '';
        $this->uniqueValue = $this->tranche . '-' . $this->systeme . '-' . $this->ligne . '-' . $this->bigramme . '-' . $this->iso . '-' . $this->repere . '-' . $this->visite . '-' . $year;
    }

    /**
     * Met à jour la date de mise à jour avant la persistance
     */
    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        $this->updatedAt = new \DateTime();
    }
}
