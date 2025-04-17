<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Communaute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Hackathon::class, inversedBy: 'communautes')]
    #[ORM\JoinColumn(name: 'id_hackathon', referencedColumnName: 'id_hackathon', onDelete: 'CASCADE')]
    private ?Hackathon $id_hackathon = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Le nom de la communauté est requis.')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Le nom doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $nom = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\NotBlank(message: 'La description est requise.')]
    #[Assert\Length(
        min: 10,
        minMessage: 'La description doit contenir au moins {{ limit }} caractères.'
    )]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date_creation;

    #[ORM\Column(type: 'boolean')]
    private bool $is_active;

    #[ORM\OneToMany(mappedBy: 'communaute_id', targetEntity: Chat::class, cascade: ['persist', 'remove'])]
    private Collection $chats;

    public function __construct()
    {
        $this->chats = new ArrayCollection();
        $this->is_active = true; // Default to active
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $value): self
    {
        $this->id = $value;
        return $this;
    }

    public function getId_hackathon(): ?Hackathon
    {
        return $this->id_hackathon;
    }

    public function setId_hackathon(?Hackathon $value): self
    {
        $this->id_hackathon = $value;
        return $this;
    }

    // Add camelCase accessor for id_hackathon
    public function getIdHackathon(): ?Hackathon
    {
        return $this->id_hackathon;
    }

    public function setIdHackathon(?Hackathon $value): self
    {
        $this->id_hackathon = $value;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $value): self
    {
        $this->nom = $value;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $value): self
    {
        $this->description = $value;
        return $this;
    }

    public function getDate_creation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDate_creation(\DateTimeInterface $value): self
    {
        $this->date_creation = $value;
        return $this;
    }
    
    // Add camelCase accessor for date_creation
    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $value): self
    {
        $this->date_creation = $value;
        return $this;
    }

    public function getIs_active(): bool
    {
        return $this->is_active;
    }

    public function setIs_active(bool $value): self
    {
        $this->is_active = $value;
        return $this;
    }
    
    // Add camelCase accessor for is_active
    public function getIsActive(): bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $value): self
    {
        $this->is_active = $value;
        return $this;
    }

    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats->add($chat);
            $chat->setCommunaute_id($this);
        }
        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            if ($chat->getCommunaute_id() === $this) {
                $chat->setCommunaute_id(null);
            }
        }
        return $this;
    }
} 