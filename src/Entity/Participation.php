<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\User;

#[ORM\Entity]
class Participation
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_participation;

        #[ORM\ManyToOne(targetEntity: Hackathon::class, inversedBy: "participations")]
    #[ORM\JoinColumn(name: 'id_hackathon', referencedColumnName: 'id_hackathon', onDelete: 'CASCADE')]
    private Hackathon $id_hackathon;

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "participations")]
    #[ORM\JoinColumn(name: 'id_participant', referencedColumnName: 'id_user', onDelete: 'CASCADE')]
    private User $id_participant;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $date_inscription;

    #[ORM\Column(type: "string", length: 20)]
    private string $statut;

    public function getId_participation()
    {
        return $this->id_participation;
    }

    public function setId_participation($value)
    {
        $this->id_participation = $value;
    }

    public function getId_hackathon()
    {
        return $this->id_hackathon;
    }

    public function setId_hackathon($value)
    {
        $this->id_hackathon = $value;
    }

    public function getId_participant()
    {
        return $this->id_participant;
    }

    public function setId_participant($value)
    {
        $this->id_participant = $value;
    }

    public function getDate_inscription()
    {
        return $this->date_inscription;
    }

    public function setDate_inscription($value)
    {
        $this->date_inscription = $value;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    public function setStatut($value)
    {
        $this->statut = $value;
    }
}
