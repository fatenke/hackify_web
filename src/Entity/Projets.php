<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Technologies;
use Doctrine\Common\Collections\Collection;
use App\Entity\Vote;

#[ORM\Entity]
class Projets
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id;

        #[ORM\ManyToOne(targetEntity: Technologies::class, inversedBy: "projetss")]
    #[ORM\JoinColumn(name: 'technologie_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Technologies $technologie_id;

    #[ORM\Column(type: "string", length: 255)]
    private string $nom;

    #[ORM\Column(type: "string", length: 255)]
    private string $statut;

    #[ORM\Column(type: "string", length: 255)]
    private string $priorite;

    #[ORM\Column(type: "string", length: 255)]
    private string $description;

    #[ORM\Column(type: "string", length: 255)]
    private string $ressource;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getTechnologie_id()
    {
        return $this->technologie_id;
    }

    public function setTechnologie_id($value)
    {
        $this->technologie_id = $value;
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
}
