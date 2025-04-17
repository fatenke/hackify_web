<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Hackathon;
use Doctrine\Common\Collections\Collection;
use App\Entity\Chat;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
class Communaute
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id;

        #[ORM\ManyToOne(targetEntity: Hackathon::class, inversedBy: "communautes")]
    #[ORM\JoinColumn(name: 'id_hackathon', referencedColumnName: 'id_hackathon', onDelete: 'CASCADE')]
    private Hackathon $id_hackathon;

    #[ORM\Column(type: "string", length: 255)]
    private string $nom;

    #[ORM\Column(type: "text")]
    private string $description;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $date_creation;

    #[ORM\Column(type: "boolean")]
    private bool $is_active;

    #[ORM\OneToMany(mappedBy: "communaute_id", targetEntity: Chat::class)]
    private Collection $chats;

    public function __construct()
    {
        $this->chats = new ArrayCollection();
    }

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

    public function getIs_active()
    {
        return $this->is_active;
    }

    public function setIs_active($value)
    {
        $this->is_active = $value;
    }

    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats->add($chat);
            $chat->setCommunaute_id($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getCommunaute_id() === $this) {
                $chat->setCommunaute_id(null);
            }
        }

        return $this;
    }
}
