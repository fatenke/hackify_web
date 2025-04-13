<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Projets;
use Doctrine\Common\Collections\Collection;
use App\Entity\Vote;

#[ORM\Entity]
class Evaluation
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id;

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "evaluations")]
    #[ORM\JoinColumn(name: 'idJury', referencedColumnName: 'id_user', onDelete: 'CASCADE')]
    private User $idJury;

        #[ORM\ManyToOne(targetEntity: Hackathon::class, inversedBy: "evaluations")]
    #[ORM\JoinColumn(name: 'idHackathon', referencedColumnName: 'id_hackathon', onDelete: 'CASCADE')]
    private Hackathon $idHackathon;

        #[ORM\ManyToOne(targetEntity: Projets::class, inversedBy: "evaluations")]
    #[ORM\JoinColumn(name: 'idProjet', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Projets $idProjet;

    #[ORM\Column(type: "float")]
    private float $NoteTech;

    #[ORM\Column(type: "float")]
    private float $NoteInnov;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getIdJury()
    {
        return $this->idJury;
    }

    public function setIdJury($value)
    {
        $this->idJury = $value;
    }

    public function getIdHackathon()
    {
        return $this->idHackathon;
    }

    public function setIdHackathon($value)
    {
        $this->idHackathon = $value;
    }

    public function getIdProjet()
    {
        return $this->idProjet;
    }

    public function setIdProjet($value)
    {
        $this->idProjet = $value;
    }

    public function getNoteTech()
    {
        return $this->NoteTech;
    }

    public function setNoteTech($value)
    {
        $this->NoteTech = $value;
    }

    public function getNoteInnov()
    {
        return $this->NoteInnov;
    }

    public function setNoteInnov($value)
    {
        $this->NoteInnov = $value;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($value)
    {
        $this->date = $value;
    }

    #[ORM\OneToMany(mappedBy: "idEvaluation", targetEntity: Vote::class)]
    private Collection $votes;

        public function getVotes(): Collection
        {
            return $this->votes;
        }
    
        public function addVote(Vote $vote): self
        {
            if (!$this->votes->contains($vote)) {
                $this->votes[] = $vote;
                $vote->setIdEvaluation($this);
            }
    
            return $this;
        }
    
        public function removeVote(Vote $vote): self
        {
            if ($this->votes->removeElement($vote)) {
                // set the owning side to null (unless already changed)
                if ($vote->getIdEvaluation() === $this) {
                    $vote->setIdEvaluation(null);
                }
            }
    
            return $this;
        }
}
