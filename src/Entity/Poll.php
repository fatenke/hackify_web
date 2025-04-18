<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Chat;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\PollOption;
use App\Entity\PollVote;

#[ORM\Entity]
class Poll
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Chat::class, inversedBy: "polls")]
    #[ORM\JoinColumn(name: 'chat_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Chat $chat_id;

    #[ORM\Column(type: "string", length: 255)]
    private string $question;

    #[ORM\Column(type: "boolean")]
    private bool $is_closed;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $created_at;

    #[ORM\OneToMany(mappedBy: "poll_id", targetEntity: PollOption::class)]
    private Collection $pollOptions;

    #[ORM\OneToMany(mappedBy: "poll_id", targetEntity: PollVote::class)]
    private Collection $poll_votes;

    public function __construct()
    {
        $this->pollOptions = new ArrayCollection();
        $this->poll_votes = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getChat_id()
    {
        return $this->chat_id;
    }

    public function setChat_id($value)
    {
        $this->chat_id = $value;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function setQuestion($value)
    {
        $this->question = $value;
    }

    public function getIs_closed()
    {
        return $this->is_closed;
    }

    public function setIs_closed($value)
    {
        $this->is_closed = $value;
    }

    public function getCreated_at()
    {
        return $this->created_at;
    }

    public function setCreated_at($value)
    {
        $this->created_at = $value;
    }

    public function getPollOptions(): Collection
    {
        return $this->pollOptions;
    }

    public function addPollOption(PollOption $pollOption): self
    {
        if (!$this->pollOptions->contains($pollOption)) {
            $this->pollOptions[] = $pollOption;
            if ($pollOption->getPoll_id() !== $this) {
                $pollOption->setPoll_id($this);
            }
        }

        return $this;
    }

    public function removePollOption(PollOption $pollOption): self
    {
        if ($this->pollOptions->removeElement($pollOption)) {
            if ($pollOption->getPoll_id() === $this) {
                $pollOption->setPoll_id(null);
            }
        }

        return $this;
    }

    public function getPoll_votes(): Collection
    {
        return $this->poll_votes;
    }

    public function addPollVote(PollVote $pollVote): self
    {
        if (!$this->poll_votes->contains($pollVote)) {
            $this->poll_votes[] = $pollVote;
            if ($pollVote->getPoll_id() !== $this) {
                $pollVote->setPoll_id($this);
            }
        }

        return $this;
    }

    public function removePollVote(PollVote $pollVote): self
    {
        if ($this->poll_votes->removeElement($pollVote)) {
            if ($pollVote->getPoll_id() === $this) {
                $pollVote->setPoll_id(null);
            }
        }

        return $this;
    }
}
