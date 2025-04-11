<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\HackathonRepository;


#[ORM\Entity(repositoryClass: HackathonRepository::class)]
class Hackathon
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_hackathon;

    #[ORM\Column(type: "integer")]
    private int $id_organisateur;

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

    #[ORM\Column(type: "text")]
    private string $conditions_participation;

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

    public function getConditions_participation()
    {
        return $this->conditions_participation;
    }

    public function setConditions_participation($value)
    {
        $this->conditions_participation = $value;
    }
}
