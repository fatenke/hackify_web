<?php

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $id = null;

    #[ORM\Column(name: 'idEvaluation')]
    private ?int $idEvaluation = null;

    #[ORM\Column(name: 'idVotant')]
    private ?int $idVotant = null;

    #[ORM\Column(name: 'idProjet')]
    private ?int $idProjet = null;

    #[ORM\Column(name: 'idHackathon')]
    private ?int $idHackathon = null;

    #[ORM\Column(name: 'valeurVote')]
    private ?float $valeurVote = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getIdEvaluation(): ?int
    {
        return $this->idEvaluation;
    }

    public function setIdEvaluation(int $idEvaluation): static
    {
        $this->idEvaluation = $idEvaluation;

        return $this;
    }

    public function getIdVotant(): ?int
    {
        return $this->idVotant;
    }

    public function setIdVotant(int $idVotant): static
    {
        $this->idVotant = $idVotant;

        return $this;
    }

    public function getIdProjet(): ?int
    {
        return $this->idProjet;
    }

    public function setIdProjet(int $idProjet): static
    {
        $this->idProjet = $idProjet;

        return $this;
    }

    public function getIdHackathon(): ?int
    {
        return $this->idHackathon;
    }

    public function setIdHackathon(int $idHackathon): static
    {
        $this->idHackathon = $idHackathon;

        return $this;
    }

    public function getValeurVote(): ?float
    {
        return $this->valeurVote;
    }

    public function setValeurVote(float $valeurVote): static
    {
        $this->valeurVote = $valeurVote;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }
}
