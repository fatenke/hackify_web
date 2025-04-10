<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvaluationRepository::class)]
class Evaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $id = null;

    #[ORM\Column(name: 'idJury')]  // Match your actual DB column name
    private ?int $idJury = null;

    #[ORM\Column(name: 'idHackathon')]
    private ?int $idHackathon = null;

    #[ORM\Column(name: 'idProjet')]
    private ?int $idProjet = null;

    #[ORM\Column(name: 'NoteTech')]
    private ?float $NoteTech = null;

    #[ORM\Column(name: 'NoteInnov')]
    private ?float $NoteInnov = null;

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

    public function getIdJury(): ?int
    {
        return $this->idJury;
    }

    public function setIdJury(int $idJury): static
    {
        $this->idJury = $idJury;

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

    public function getIdProjet(): ?int
    {
        return $this->idProjet;
    }

    public function setIdProjet(int $idProjet): static
    {
        $this->idProjet = $idProjet;

        return $this;
    }

    public function getNoteTech(): ?float
    {
        return $this->NoteTech;
    }

    public function setNoteTech(float $NoteTech): static
    {
        $this->NoteTech = $NoteTech;

        return $this;
    }

    public function getNoteInnov(): ?float
    {
        return $this->NoteInnov;
    }

    public function setNoteInnov(float $NoteInnov): static
    {
        $this->NoteInnov = $NoteInnov;

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
