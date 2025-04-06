<?php

namespace App\Entity;

use App\Repository\RadiogrammeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RadiogrammeRepository::class)]
#[ORM\Table(name: 'radiogrammes')]
#[ORM\HasLifecycleCallbacks]
class Radiogramme
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

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $pp = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $iso = null;

    #[ORM\Column(length: 20)]
    private ?string $repere = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $visite = null;

    #[ORM\Column(length: 5)]
    private ?string $traversee = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $armoire = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $etagere = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $boite = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $oi = null; // ? a analyser ...

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rf = null; // ? a analyser ...

    #[ORM\Column]
    private ?bool $isIps = null;

    #[ORM\Column]
    private ?bool $isQs = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cpp = null; // ? a analyser ...

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $csp = null; // ? a analyser ...

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commande = null;

    #[ORM\Column(length: 255)]
    private ?string $titulaire = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observation = null;

    #[ORM\Column(length: 255, nullable: true, unique: true)]
    private ?string $uniqueValue = null;

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

    public function getPp(): ?string
    {
        return $this->pp;
    }

    public function setPp(?string $pp): static
    {
        $this->pp = $pp;

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

    public function setTraversee(string $traversee): static
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
        // $uniqueValue = tranche + systeme + ligne + bigramme + iso + repere
        return $this->uniqueValue;
    }

    public function setUniqueValue(?string $uniqueValue): static
    {
        $this->uniqueValue = $uniqueValue;

        return $this;
    }

    /**
     * Génère la valeur unique calculée à partir des propriétés de l'entité
     */
    public function calculateUniqueValue(): string
    {
        return $this->tranche . '-' . $this->systeme . '-' . $this->ligne . '-' . $this->bigramme . '-' . $this->iso . '-' . $this->repere . '-' . $this->visite . '-' . $this->date->format('Y');
    }

    /**
     * Génère automatiquement la valeur unique avant la persistance
     */
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function generateUniqueValue(): void
    {
        // Génère la valeur unique à partir des champs qui identifient la soudure
        // $this->uniqueValue = $this->getUniqueValue();
        $this->uniqueValue = $this->calculateUniqueValue();
    }
}
