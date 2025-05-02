<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Projets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le nom du projet est requis.")]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ]+(?:\\s[a-zA-ZÀ-ÿ]+)*$/u",
        message: "Le nom du projet ne doit contenir que des lettres sans aucun caractère spécial.",
        groups: ["create", "update"]
    )]
    private string $nom;

    #[ORM\Column(type: "string", length: 255)]
 
    private string $statut;

    #[ORM\Column(type: "string", length: 255)]

    private string $priorite;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "La description est requise.")]
    #[Assert\Length(
        min: 10,
        max: 500,
        minMessage: "La description doit contenir au moins {{ limit }} caractères.",
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $description;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "La ressource est requise.")]
    #[Assert\Url(message: "L'URL de la ressource n'est pas valide.")]
    private string $ressource;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

   

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($value)
    {
        $this->nom = $value;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    public function setStatut($value)
    {
        $this->statut = $value;
    }

    public function getPriorite()
    {
        return $this->priorite;
    }

    public function setPriorite($value)
    {
        $this->priorite = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getRessource()
    {
        return $this->ressource;
    }

    public function setRessource($value)
    {
        $this->ressource = $value;
    }

    #[ORM\OneToMany(mappedBy: "idProjet", targetEntity: Evaluation::class)]
    private Collection $evaluations;

        public function getEvaluations(): Collection
        {
            return $this->evaluations;
        }
    
        public function addEvaluation(Evaluation $evaluation): self
        {
            if (!$this->evaluations->contains($evaluation)) {
                $this->evaluations[] = $evaluation;
                $evaluation->setIdProjet($this);
            }
    
            return $this;
        }
    
        public function removeEvaluation(Evaluation $evaluation): self
        {
            if ($this->evaluations->removeElement($evaluation)) {
                // set the owning side to null (unless already changed)
                if ($evaluation->getIdProjet() === $this) {
                    $evaluation->setIdProjet(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "idProjet", targetEntity: Vote::class)]
    private Collection $votes;

    /**
     * @var Collection<int, Technologies>
     */
    #[ORM\ManyToMany(targetEntity: Technologies::class, inversedBy: 'projets')]
    private Collection $technologies;

    #[ORM\ManyToOne(inversedBy: 'projets')]
    #[ORM\JoinColumn(name: 'id_hackathon', referencedColumnName: 'id_hackathon')]
    private ?Hackathon $id_hack = null;
    

    public function __construct()
    {
        $this->technologies = new ArrayCollection();
    }

    /**
     * @return Collection<int, Technologies>
     */
    public function getTechnologies(): Collection
    {
        return $this->technologies;
    }

    public function addTechnology(Technologies $technology): static
    {
        if (!$this->technologies->contains($technology)) {
            $this->technologies->add($technology);
        }

        return $this;
    }

    public function removeTechnology(Technologies $technology): static
    {
        $this->technologies->removeElement($technology);

        return $this;
    }

    public function getIdHack(): ?Hackathon
    {
        return $this->id_hack;
    }

    public function setIdHack(?Hackathon $id_hack): static
    {
        $this->id_hack = $id_hack;

        return $this;
    }
    
}
