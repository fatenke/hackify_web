<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;

use Doctrine\Common\Collections\Collection;
use App\Entity\Vote;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "integer")]
    private int $tel;

    #[ORM\Column(type: "string", length: 50)]
    private string $nom;

    #[ORM\Column(type: "string", length: 50)]
    private string $prenom;

    #[ORM\Column(type: "string", length: 50)]
    private string $adresse;

    #[ORM\Column(type: "string", length: 70)]
    private string $email;

    #[ORM\Column(type: "string", length: 20)]
    private string $role;

    #[ORM\Column(type: "string", length: 20)]
    private string $mdp;

    #[ORM\Column(type: "string", length: 100)]
    private string $photo;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getTel()
    {
        return $this->tel;
    }

    public function setTel($value)
    {
        $this->tel = $value;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($value)
    {
        $this->nom = $value;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($value)
    {
        $this->prenom = $value;
    }

    public function getAdresse()
    {
        return $this->adresse;
    }

    public function setAdresse($value)
    {
        $this->adresse = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($value)
    {
        $this->role = $value;
    }

    public function getMdp()
    {
        return $this->mdp;
    }

    public function setMdp($value)
    {
        $this->mdp = $value;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($value)
    {
        $this->photo = $value;
    }

    #[ORM\OneToMany(mappedBy: "posted_by", targetEntity: Message::class)]
    private Collection $messages;

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setPosted_by($this);
        }
        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            if ($message->getPosted_by() === $this) {
                $message->setPosted_by(null);
            }
        }
        return $this;
    }

    #[ORM\OneToMany(mappedBy: "user_id", targetEntity: Vote::class)]
    private Collection $votes;

    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setUser_id($this);
        }
        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            if ($vote->getUser_id() === $this) {
                $vote->setUser_id(null);
            }
        }
        return $this;
    }
}
