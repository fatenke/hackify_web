<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Chat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Communaute::class, inversedBy: 'chats')]
    #[ORM\JoinColumn(name: 'communaute_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?Communaute $communaute_id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $nom;

    #[ORM\Column(type: 'string')]
    private string $type;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date_creation;

    #[ORM\Column(type: 'boolean')]
    private bool $is_active;

    #[ORM\OneToMany(mappedBy: 'chat_id', targetEntity: Poll::class, cascade: ['persist', 'remove'])]
    private Collection $polls;

    #[ORM\OneToMany(mappedBy: 'chat_id', targetEntity: Message::class, cascade: ['persist', 'remove'])]
    private Collection $messages;

    public function __construct()
    {
        $this->polls = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->is_active = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $value): self
    {
        $this->id = $value;
        return $this;
    }

    public function getCommunaute_id(): ?Communaute
    {
        return $this->communaute_id;
    }

    public function setCommunaute_id(?Communaute $value): self
    {
        $this->communaute_id = $value;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $value): self
    {
        $this->nom = $value;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $value): self
    {
        $this->type = $value;
        return $this;
    }

    public function getDate_creation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDate_creation(\DateTimeInterface $value): self
    {
        $this->date_creation = $value;
        return $this;
    }

    public function getIs_active(): bool
    {
        return $this->is_active;
    }

    public function setIs_active(bool $value): self
    {
        $this->is_active = $value;
        return $this;
    }

    public function getPolls(): Collection
    {
        return $this->polls;
    }

    public function addPoll(Poll $poll): self
    {
        if (!$this->polls->contains($poll)) {
            $this->polls->add($poll);
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

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
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