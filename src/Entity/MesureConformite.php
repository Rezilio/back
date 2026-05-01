<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use App\Repository\MesureConformiteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MesureConformiteRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['mesure:read']]),
        new Get(normalizationContext: ['groups' => ['mesure:read', 'mesure:read:detail']]),
        new Post(denormalizationContext: ['groups' => ['mesure:write']], security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_RSSI')"),
        new Patch(denormalizationContext: ['groups' => ['mesure:write']], security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_RSSI')"),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['module' => 'exact', 'statut' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['code', 'module', 'statut'])]
class MesureConformite
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['mesure:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['gouvernance', 'gestion_risques', 'incidents', 'supply_chain', 'continuite', 'cryptographie', 'controle_acces', 'vulnerabilites'])]
    #[Groups(['mesure:read', 'mesure:write'])]
    private ?string $module = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Groups(['mesure:read', 'mesure:write'])]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['mesure:read', 'mesure:write'])]
    private ?string $titre = null;

    #[ORM\Column(type: 'text')]
    #[Groups(['mesure:read:detail', 'mesure:write'])]
    private ?string $description = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: ['conforme', 'partiel', 'non_conforme', 'na'])]
    #[Groups(['mesure:read', 'mesure:write'])]
    private string $statut = 'non_conforme';

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['mesure:read', 'mesure:write'])]
    private ?string $responsable = null;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups(['mesure:read', 'mesure:write'])]
    private ?\DateTimeInterface $echeance = null;

    #[ORM\Column(type: 'json')]
    #[Groups(['mesure:read:detail', 'mesure:write'])]
    private array $preuves = [];

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['mesure:read:detail', 'mesure:write'])]
    private ?string $commentaire = null;

    #[ORM\Column]
    #[Groups(['mesure:read'])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    #[Groups(['mesure:read'])]
    private \DateTimeImmutable $updatedAt;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?Uuid { return $this->id; }
    public function getModule(): ?string { return $this->module; }
    public function setModule(string $module): static { $this->module = $module; return $this; }
    public function getCode(): ?string { return $this->code; }
    public function setCode(string $code): static { $this->code = $code; return $this; }
    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }
    public function getStatut(): string { return $this->statut; }
    public function setStatut(string $statut): static { $this->statut = $statut; return $this; }
    public function getResponsable(): ?string { return $this->responsable; }
    public function setResponsable(?string $responsable): static { $this->responsable = $responsable; return $this; }
    public function getEcheance(): ?\DateTimeInterface { return $this->echeance; }
    public function setEcheance(?\DateTimeInterface $echeance): static { $this->echeance = $echeance; return $this; }
    public function getPreuves(): array { return $this->preuves; }
    public function setPreuves(array $preuves): static { $this->preuves = $preuves; return $this; }
    public function getCommentaire(): ?string { return $this->commentaire; }
    public function setCommentaire(?string $commentaire): static { $this->commentaire = $commentaire; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
}
