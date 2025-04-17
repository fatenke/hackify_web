<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Poll
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Chat::class, inversedBy: 'polls')]
    #[ORM\JoinColumn(name: 'chat_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?Chat $chat_id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $question;

    #[ORM\Column(type: 'boolean')]
    private bool $is_closed;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $created_at;

    #[ORM\OneToMany(mappedBy: 'poll_id', targetEntity: PollOption::class, cascade: ['persist', 'remove'])]
    private Collection $poll_options;

    #[ORM\OneToMany(mappedBy: 'poll_id', targetEntity: PollVote::class, cascade: ['persist', 'remove'])]
    private Collection $poll_votes;

    public function __construct()
    {
        $this->poll_options = new ArrayCollection();
        $this->poll_votes = new ArrayCollection();
        $this->is_closed = false; // Default to open
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

    public function getChat_id(): ?Chat
    {
        return $this->chat_id;
    }

    public function setChat_id(?Chat $value): self
    {
        $this->chat_id = $value;
        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $value): self
    {
        $this->question = $value;
        return $this;
    }

    public function getIs_closed(): bool
    {
        return $this->is_closed;
    }

    public function setIs_closed(bool $value): self
    {
        $this->is_closed = $value;
        return $this;
    }

    public function getCreated_at(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreated_at(\DateTimeInterface $value): self
    {
        $this->created_at = $value;
        return $this;
    }

    public function getPoll_options(): Collection
    {
        return $this->poll_options;
    }

    public function addPoll_option(PollOption $poll_option): self
    {
        if (!$this->poll_options->contains($poll_option)) {
            $this->poll_options->add($poll_option);
            $poll_option->setPoll_id($this);
        }
        return $this;
    }

    public function removePoll_option(PollOption $poll_option): self
    {
        if ($this->poll_options->removeElement($poll_option)) {
            if ($poll_option->getPoll_id() === $this) {
                $poll_option->setPoll_id(null);
            }
        }
        return $this;
    }

    public function getPoll_votes(): Collection
    {
        return $this->poll_votes;
    }

    public function addPoll_vote(PollVote $poll_vote): self
    {
        if (!$this->poll_votes->contains($poll_vote)) {
            $this->poll_votes->add($poll_vote);
            $poll_vote->setPoll_id($this);
        }
        return $this;
    }

    public function removePoll_vote(PollVote $poll_vote): self
    {
        if ($this->poll_votes->removeElement($poll_vote)) {
            if ($poll_vote->getPoll_id() === $this) {
                $poll_vote->setPoll_id(null);
            }
        }
        return $this;
    }
}