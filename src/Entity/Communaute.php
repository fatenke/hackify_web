<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommunauteRepository;

use Doctrine\Common\Collections\Collection;
use App\Entity\Chat;

#[ORM\Entity(repositoryClass: CommunauteRepository::class)]
class Communaute
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "integer")]
    private int $id_hackathon;

    #[ORM\Column(type: "string", length: 255)]
    private string $nom;

    #[ORM\Column(type: "text")]
    private string $description;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $date_creation;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getId_hackathon()
    {
        return $this->id_hackathon;
    }

    public function setId_hackathon($value)
    {
        $this->id_hackathon = $value;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($value)
    {
        $this->nom = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getDate_creation()
    {
        return $this->date_creation;
    }

    public function setDate_creation($value)
    {
        $this->date_creation = $value;
    }

    #[ORM\OneToMany(mappedBy: "communaute_id", targetEntity: Chat::class)]
    private Collection $chats;

    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->setCommunaute_id($this);
        }
        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            if ($chat->getCommunaute_id() === $this) {
                $chat->setCommunaute_id(null);
            }
        }
        return $this;
    }
}
