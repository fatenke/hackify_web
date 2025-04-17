<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Chat;
use Doctrine\Common\Collections\Collection;
use App\Entity\Poll_votes;

#[ORM\Entity]
class Polls
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id;

        #[ORM\ManyToOne(targetEntity: Chat::class, inversedBy: "pollss")]
    #[ORM\JoinColumn(name: 'chat_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Chat $chat_id;

    #[ORM\Column(type: "string", length: 255)]
    private string $question;

    #[ORM\Column(type: "boolean")]
    private bool $is_closed;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $created_at;

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

    #[ORM\OneToMany(mappedBy: "poll_id", targetEntity: Poll_options::class)]
    private Collection $poll_optionss;

        public function getPoll_optionss(): Collection
        {
            return $this->poll_optionss;
        }
    
        public function addPoll_options(Poll_options $poll_options): self
        {
            if (!$this->poll_optionss->contains($poll_options)) {
                $this->poll_optionss[] = $poll_options;
                $poll_options->setPoll_id($this);
            }
    
            return $this;
        }
    
        public function removePoll_options(Poll_options $poll_options): self
        {
            if ($this->poll_optionss->removeElement($poll_options)) {
                // set the owning side to null (unless already changed)
                if ($poll_options->getPoll_id() === $this) {
                    $poll_options->setPoll_id(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "poll_id", targetEntity: Poll_votes::class)]
    private Collection $poll_votess;
}
