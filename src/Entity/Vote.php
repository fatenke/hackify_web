<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Hackathon;

#[ORM\Entity]
class Vote
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id;

        #[ORM\ManyToOne(targetEntity: Evaluation::class, inversedBy: "votes")]
    #[ORM\JoinColumn(name: 'idEvaluation', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Evaluation $idEvaluation;

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "votes")]
    #[ORM\JoinColumn(name: 'idVotant', referencedColumnName: 'id_user', onDelete: 'CASCADE')]
    private User $idVotant;

        #[ORM\ManyToOne(targetEntity: Projets::class, inversedBy: "votes")]
    #[ORM\JoinColumn(name: 'idProjet', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Projets $idProjet;

        #[ORM\ManyToOne(targetEntity: Hackathon::class, inversedBy: "votes")]
    #[ORM\JoinColumn(name: 'idHackathon', referencedColumnName: 'id_hackathon', onDelete: 'CASCADE')]
    private Hackathon $idHackathon;

    #[ORM\Column(type: "float")]
    private float $valeurVote;

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

    public function getIdEvaluation()
    {
        return $this->idEvaluation;
    }

    public function setIdEvaluation($value)
    {
        $this->idEvaluation = $value;
    }

    public function getIdVotant()
    {
        return $this->idVotant;
    }

    public function setIdVotant($value)
    {
        $this->idVotant = $value;
    }

    public function getIdProjet()
    {
        return $this->idProjet;
    }

    public function setIdProjet($value)
    {
        $this->idProjet = $value;
    }

    public function getIdHackathon()
    {
        return $this->idHackathon;
    }

    public function setIdHackathon($value)
    {
        $this->idHackathon = $value;
    }

    public function getValeurVote()
    {
        return $this->valeurVote;
    }

    public function setValeurVote($value)
    {
        $this->valeurVote = $value;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($value)
    {
        $this->date = $value;
    }
}
