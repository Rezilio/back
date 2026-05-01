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
use App\Repository\FournisseurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FournisseurRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['fournisseur:read']]),
        new Get(normalizationContext: ['groups' => ['fournisseur:read', 'fournisseur:read:detail']]),
        new Post(denormalizationContext: ['groups' => ['fournisseur:write']]),
        new Patch(denormalizationContext: ['groups' => ['fournisseur:write']]),
        new Delete(),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['criticite' => 'exact', 'statutConformite' => 'exact'])]
class Fournisseur
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['fournisseur:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['fournisseur:read', 'fournisseur:write'])]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    #[Groups(['fournisseur:read', 'fournisseur:write'])]
    private ?string $type = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: ['critique', 'important', 'standard'])]
    #[Groups(['fournisseur:read', 'fournisseur:write'])]
    private string $criticite = 'standard';

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: ['conforme', 'partiel', 'non_conforme', 'na'])]
    #[Groups(['fournisseur:read', 'fournisseur:write'])]
    private string $statutConformite = 'non_conforme';

    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups(['fournisseur:read', 'fournisseur:write'])]
    private ?\DateTimeInterface $dateEvaluation = null;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups(['fournisseur:read', 'fournisseur:write'])]
    private ?\DateTimeInterface $prochainEvaluation = null;

    #[ORM\Column]
    #[Groups(['fournisseur:read', 'fournisseur:write'])]
    private bool $contratSecurite = false;

    #[ORM\Column]
    #[Groups(['fournisseur:read'])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    #[Groups(['fournisseur:read'])]
    private \DateTimeImmutable $updatedAt;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void { $this->createdAt = new \DateTimeImmutable(); $this->updatedAt = new \DateTimeImmutable(); }
    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void { $this->updatedAt = new \DateTimeImmutable(); }

    public function getId(): ?Uuid { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }
    public function getType(): ?string { return $this->type; }
    public function setType(string $type): static { $this->type = $type; return $this; }
    public function getCriticite(): string { return $this->criticite; }
    public function setCriticite(string $criticite): static { $this->criticite = $criticite; return $this; }
    public function getStatutConformite(): string { return $this->statutConformite; }
    public function setStatutConformite(string $statutConformite): static { $this->statutConformite = $statutConformite; return $this; }
    public function getDateEvaluation(): ?\DateTimeInterface { return $this->dateEvaluation; }
    public function setDateEvaluation(?\DateTimeInterface $date): static { $this->dateEvaluation = $date; return $this; }
    public function getProchainEvaluation(): ?\DateTimeInterface { return $this->prochainEvaluation; }
    public function setProchainEvaluation(?\DateTimeInterface $date): static { $this->prochainEvaluation = $date; return $this; }
    public function isContratSecurite(): bool { return $this->contratSecurite; }
    public function setContratSecurite(bool $contratSecurite): static { $this->contratSecurite = $contratSecurite; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
}
