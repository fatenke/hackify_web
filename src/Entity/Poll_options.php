<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Polls;
use Doctrine\Common\Collections\Collection;
use App\Entity\Poll_votes;

#[ORM\Entity]
class Poll_options
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id;

        #[ORM\ManyToOne(targetEntity: Polls::class, inversedBy: "poll_optionss")]
    #[ORM\JoinColumn(name: 'poll_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Polls $poll_id;

    #[ORM\Column(type: "string", length: 255)]
    private string $text;

    #[ORM\Column(type: "integer")]
    private int $vote_count;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getPoll_id()
    {
        return $this->poll_id;
    }

    public function setPoll_id($value)
    {
        $this->poll_id = $value;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($value)
    {
        $this->text = $value;
    }

    public function getVote_count()
    {
        return $this->vote_count;
    }

    public function setVote_count($value)
    {
        $this->vote_count = $value;
    }

    #[ORM\OneToMany(mappedBy: "option_id", targetEntity: Poll_votes::class)]
    private Collection $poll_votess;

        public function getPoll_votess(): Collection
        {
            return $this->poll_votess;
        }
    
        public function addPoll_votes(Poll_votes $poll_votes): self
        {
            if (!$this->poll_votess->contains($poll_votes)) {
                $this->poll_votess[] = $poll_votes;
                $poll_votes->setOption_id($this);
            }
    
            return $this;
        }
    
        public function removePoll_votes(Poll_votes $poll_votes): self
        {
            if ($this->poll_votess->removeElement($poll_votes)) {
                // set the owning side to null (unless already changed)
                if ($poll_votes->getOption_id() === $this) {
                    $poll_votes->setOption_id(null);
                }
            }
    
            return $this;
        }
}
