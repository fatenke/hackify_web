<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use App\Entity\Communaute;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\HackathonRepository;

#[ORM\Entity(repositoryClass: HackathonRepository::class)]
#[ORM\Table(name: 'hackathon')]
class Hackathon
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_hackathon = null;
    public function getId_hackathon(): ?int
    {
        return $this->id_hackathon;
    }

    public function setId_hackathon(int $id_hackathon): self
    {
        $this->id_hackathon = $id_hackathon;
        return $this;
    }

    
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "hackathons")]
    #[ORM\JoinColumn(name: 'id_organisateur', referencedColumnName: 'id_user', onDelete: 'CASCADE')]
    private User $id_organisateur;
    public function getId_organisateur(): ?int
    {
        return $this->id_organisateur;
    }

    public function setId_organisateur(int $id_organisateur): self
    {
        $this->id_organisateur = $id_organisateur;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'Le nom du hackathon est requis.')]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'Le nom doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $nom_hackathon = null;

    public function getNom_hackathon(): ?string
    {
        return $this->nom_hackathon;
    }

    public function setNom_hackathon(string $nom_hackathon): self
    {
        $this->nom_hackathon = $nom_hackathon;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: false)]
    #[Assert\NotBlank(message: 'La description est requise.')]
    #[Assert\Length(
        min: 10,
        minMessage: 'La description doit contenir au moins {{ limit }} caractères.'
)]
    private ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $date_debut = null;

    public function getDate_debut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDate_debut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $date_fin = null;

    public function getDate_fin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDate_fin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $lieu = null;

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $theme = null;

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): self
    {
        $this->theme = $theme;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $max_participants = null;

    public function getMax_participants(): ?int
    {
        return $this->max_participants;
    }

    public function setMax_participants(int $max_participants): self
    {
        $this->max_participants = $max_participants;
        return $this;
    }


    

    public function getIdHackathon(): ?int
    {
        return $this->id_hackathon;
    }

    public function getIdOrganisateur(): ?int
    {
        return $this->id_organisateur;
    }

    public function setIdOrganisateur(int $id_organisateur): static
    {
        $this->id_organisateur = $id_organisateur;

        return $this;
    }

    public function getNomHackathon(): ?string
    {
        return $this->nom_hackathon;
    }

    public function setNomHackathon(string $nom_hackathon): static
    {
        $this->nom_hackathon = $nom_hackathon;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getMaxParticipants(): ?int
    {
        return $this->max_participants;
    }

    public function setMaxParticipants(int $max_participants): static
    {
        $this->max_participants = $max_participants;

        return $this;
    }

    
    



    #[ORM\OneToMany(mappedBy: "id_hackathon", targetEntity: Communaute::class)]
    private Collection $communautes;

    
    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'hackathon')]
    private Collection $participations;

    public function __construct()
    {
        $this->participations = new ArrayCollection();
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        if (!$this->participations instanceof Collection) {
            $this->participations = new ArrayCollection();
        }
        return $this->participations;
    }

    public function addParticipation(Participation $participation): self
    {
        if (!$this->getParticipations()->contains($participation)) {
            $this->getParticipations()->add($participation);
        }
        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        $this->getParticipations()->removeElement($participation);
        return $this;
    }



    #[ORM\OneToMany(mappedBy: "idHackathon", targetEntity: Evaluation::class)]
    private Collection $evaluations;

        public function getEvaluations(): Collection
        {
            return $this->evaluations;
        }
    
        public function addEvaluation(Evaluation $evaluation): self
        {
            if (!$this->evaluations->contains($evaluation)) {
                $this->evaluations[] = $evaluation;
                $evaluation->setIdHackathon($this);
            }
    
            return $this;
        }
    
        public function removeEvaluation(Evaluation $evaluation): self
        {
            if ($this->evaluations->removeElement($evaluation)) {
                // set the owning side to null (unless already changed)
                if ($evaluation->getIdHackathon() === $this) {
                    $evaluation->setIdHackathon(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "idHackathon", targetEntity: Vote::class)]
    private Collection $votes;
}
