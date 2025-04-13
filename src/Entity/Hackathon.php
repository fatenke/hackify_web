<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use App\Entity\Communaute;

#[ORM\Entity]
class Hackathon
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_hackathon;

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "hackathons")]
    #[ORM\JoinColumn(name: 'id_organisateur', referencedColumnName: 'id_user', onDelete: 'CASCADE')]
    private User $id_organisateur;

    #[ORM\Column(type: "string", length: 255)]
    private string $nom_hackathon;

    #[ORM\Column(type: "text")]
    private string $description;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $date_debut;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $date_fin;

    #[ORM\Column(type: "string", length: 255)]
    private string $lieu;

    #[ORM\Column(type: "string", length: 255)]
    private string $theme;

    #[ORM\Column(type: "integer")]
    private int $max_participants;

    public function getId_hackathon()
    {
        return $this->id_hackathon;
    }

    public function setId_hackathon($value)
    {
        $this->id_hackathon = $value;
    }

    public function getId_organisateur()
    {
        return $this->id_organisateur;
    }

    public function setId_organisateur($value)
    {
        $this->id_organisateur = $value;
    }

    public function getNom_hackathon()
    {
        return $this->nom_hackathon;
    }

    public function setNom_hackathon($value)
    {
        $this->nom_hackathon = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getDate_debut()
    {
        return $this->date_debut;
    }

    public function setDate_debut($value)
    {
        $this->date_debut = $value;
    }

    public function getDate_fin()
    {
        return $this->date_fin;
    }

    public function setDate_fin($value)
    {
        $this->date_fin = $value;
    }

    public function getLieu()
    {
        return $this->lieu;
    }

    public function setLieu($value)
    {
        $this->lieu = $value;
    }

    public function getTheme()
    {
        return $this->theme;
    }

    public function setTheme($value)
    {
        $this->theme = $value;
    }

    public function getMax_participants()
    {
        return $this->max_participants;
    }

    public function setMax_participants($value)
    {
        $this->max_participants = $value;
    }

    #[ORM\OneToMany(mappedBy: "id_hackathon", targetEntity: Communaute::class)]
    private Collection $communautes;

    #[ORM\OneToMany(mappedBy: "id_hackathon", targetEntity: Participation::class)]
    private Collection $participations;

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
