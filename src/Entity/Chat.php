<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ChatRepository;

use App\Entity\Communaute;
use Doctrine\Common\Collections\Collection;
use App\Entity\Message;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
class Chat
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id;

        #[ORM\ManyToOne(targetEntity: Communaute::class, inversedBy: "chats")]
    #[ORM\JoinColumn(name: 'communaute_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Communaute $communaute_id;

    #[ORM\Column(type: "string", length: 255)]
    private string $nom;

    #[ORM\Column(type: "string")]
    private string $type;

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

    public function getCommunaute_id()
    {
        return $this->communaute_id;
    }

    public function setCommunaute_id($value)
    {
        $this->communaute_id = $value;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($value)
    {
        $this->nom = $value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($value)
    {
        $this->type = $value;
    }

    public function getDate_creation()
    {
        return $this->date_creation;
    }

    public function setDate_creation($value)
    {
        $this->date_creation = $value;
    }

    #[ORM\OneToMany(mappedBy: "chat_id", targetEntity: Poll::class)]
    private Collection $polls;

    public function getPolls(): Collection
    {
        return $this->polls;
    }

    public function addPoll(Poll $poll): self
    {
        if (!$this->polls->contains($poll)) {
            $this->polls[] = $poll;
            $poll->setChat_id($this);
        }
        return $this;
    }

    public function removePoll(Poll $poll): self
    {
        if ($this->polls->removeElement($poll)) {
            if ($poll->getChat_id() === $this) {
                $poll->setChat_id(null);
            }
        }
        return $this;
    }

    #[ORM\OneToMany(mappedBy: "chat_id", targetEntity: Message::class)]
    private Collection $messages;

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setChat_id($this);
        }
        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            if ($message->getChat_id() === $this) {
                $message->setChat_id(null);
            }
        }
        return $this;
    }
}
