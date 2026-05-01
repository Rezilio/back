<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\RisqueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RisqueRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['risque:read']]),
        new Get(normalizationContext: ['groups' => ['risque:read', 'risque:read:detail']]),
        new Post(denormalizationContext: ['groups' => ['risque:write']]),
        new Patch(denormalizationContext: ['groups' => ['risque:write']]),
        new Delete(),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['niveau' => 'exact', 'statut' => 'exact', 'traitement' => 'exact'])]
class Risque
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['risque:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['risque:read', 'risque:write'])]
    private ?string $titre = null;

    #[ORM\Column(type: 'text')]
    #[Groups(['risque:read:detail', 'risque:write'])]
    private ?string $description = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: ['critique', 'eleve', 'moyen', 'faible'])]
    #[Groups(['risque:read', 'risque:write'])]
    private ?string $niveau = null;

    #[ORM\Column]
    #[Assert\Range(min: 1, max: 5)]
    #[Groups(['risque:read', 'risque:write'])]
    private int $probabilite = 1;

    #[ORM\Column]
    #[Assert\Range(min: 1, max: 5)]
    #[Groups(['risque:read', 'risque:write'])]
    private int $impact = 1;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: ['accepter', 'reduire', 'transferer', 'eviter'])]
    #[Groups(['risque:read', 'risque:write'])]
    private string $traitement = 'reduire';

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['risque:read', 'risque:write'])]
    private ?string $responsable = null;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups(['risque:read', 'risque:write'])]
    private ?\DateTimeInterface $echeance = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: ['ouvert', 'en_traitement', 'traite'])]
    #[Groups(['risque:read', 'risque:write'])]
    private string $statut = 'ouvert';

    #[ORM\Column]
    #[Groups(['risque:read'])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    #[Groups(['risque:read'])]
    private \DateTimeImmutable $updatedAt;

    public function getScore(): int { return $this->probabilite * $this->impact; }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void { $this->createdAt = new \DateTimeImmutable(); $this->updatedAt = new \DateTimeImmutable(); }
    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void { $this->updatedAt = new \DateTimeImmutable(); }

    public function getId(): ?Uuid { return $this->id; }
    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }
    public function getNiveau(): ?string { return $this->niveau; }
    public function setNiveau(string $niveau): static { $this->niveau = $niveau; return $this; }
    public function getProbabilite(): int { return $this->probabilite; }
    public function setProbabilite(int $probabilite): static { $this->probabilite = $probabilite; return $this; }
    public function getImpact(): int { return $this->impact; }
    public function setImpact(int $impact): static { $this->impact = $impact; return $this; }
    public function getTraitement(): string { return $this->traitement; }
    public function setTraitement(string $traitement): static { $this->traitement = $traitement; return $this; }
    public function getResponsable(): ?string { return $this->responsable; }
    public function setResponsable(?string $responsable): static { $this->responsable = $responsable; return $this; }
    public function getEcheance(): ?\DateTimeInterface { return $this->echeance; }
    public function setEcheance(?\DateTimeInterface $echeance): static { $this->echeance = $echeance; return $this; }
    public function getStatut(): string { return $this->statut; }
    public function setStatut(string $statut): static { $this->statut = $statut; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
}
