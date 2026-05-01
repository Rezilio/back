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
use App\Repository\IncidentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IncidentRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['incident:read']]),
        new Get(normalizationContext: ['groups' => ['incident:read', 'incident:read:detail']]),
        new Post(denormalizationContext: ['groups' => ['incident:write']]),
        new Patch(denormalizationContext: ['groups' => ['incident:write']]),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['statut' => 'exact', 'severite' => 'exact'])]
class Incident
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['incident:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['incident:read', 'incident:write'])]
    private ?string $titre = null;

    #[ORM\Column(type: 'text')]
    #[Groups(['incident:read:detail', 'incident:write'])]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull]
    #[Groups(['incident:read', 'incident:write'])]
    private ?\DateTimeImmutable $dateDetection = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['incident:read', 'incident:write'])]
    private ?\DateTimeImmutable $dateDeclaration = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: ['ouvert', 'en_cours', 'resolu', 'clos'])]
    #[Groups(['incident:read', 'incident:write'])]
    private string $statut = 'ouvert';

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: ['critique', 'majeur', 'mineur'])]
    #[Groups(['incident:read', 'incident:write'])]
    private string $severite = 'mineur';

    #[ORM\Column]
    #[Groups(['incident:read', 'incident:write'])]
    private bool $notificationANSSI = false;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['incident:read', 'incident:write'])]
    private ?\DateTimeImmutable $dateNotificationANSSI = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['incident:read', 'incident:write'])]
    private ?string $responsable = null;

    #[ORM\Column(type: 'json')]
    #[Groups(['incident:read:detail', 'incident:write'])]
    private array $actions = [];

    #[ORM\Column]
    #[Groups(['incident:read'])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    #[Groups(['incident:read'])]
    private \DateTimeImmutable $updatedAt;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void { $this->createdAt = new \DateTimeImmutable(); $this->updatedAt = new \DateTimeImmutable(); }
    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void { $this->updatedAt = new \DateTimeImmutable(); }

    public function getId(): ?Uuid { return $this->id; }
    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }
    public function getDateDetection(): ?\DateTimeImmutable { return $this->dateDetection; }
    public function setDateDetection(\DateTimeImmutable $dateDetection): static { $this->dateDetection = $dateDetection; return $this; }
    public function getDateDeclaration(): ?\DateTimeImmutable { return $this->dateDeclaration; }
    public function setDateDeclaration(?\DateTimeImmutable $dateDeclaration): static { $this->dateDeclaration = $dateDeclaration; return $this; }
    public function getStatut(): string { return $this->statut; }
    public function setStatut(string $statut): static { $this->statut = $statut; return $this; }
    public function getSeverite(): string { return $this->severite; }
    public function setSeverite(string $severite): static { $this->severite = $severite; return $this; }
    public function isNotificationANSSI(): bool { return $this->notificationANSSI; }
    public function setNotificationANSSI(bool $notificationANSSI): static { $this->notificationANSSI = $notificationANSSI; return $this; }
    public function getDateNotificationANSSI(): ?\DateTimeImmutable { return $this->dateNotificationANSSI; }
    public function setDateNotificationANSSI(?\DateTimeImmutable $date): static { $this->dateNotificationANSSI = $date; return $this; }
    public function getResponsable(): ?string { return $this->responsable; }
    public function setResponsable(?string $responsable): static { $this->responsable = $responsable; return $this; }
    public function getActions(): array { return $this->actions; }
    public function setActions(array $actions): static { $this->actions = $actions; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
}
