<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\Poll_optionRepository;

use App\Entity\Poll;
use Doctrine\Common\Collections\Collection;
use App\Entity\Vote;

#[ORM\Entity(repositoryClass: Poll_optionRepository::class)]
#[ORM\Table(name: 'poll_options')]
class Poll_option
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Poll::class, inversedBy: "poll_options")]
    #[ORM\JoinColumn(name: 'poll_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Poll $poll_id;

    #[ORM\Column(type: "string", length: 255)]
    private string $text;

    #[ORM\Column(type: "integer")]
    private int $vote_count = 0;

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

    #[ORM\OneToMany(mappedBy: "option_id", targetEntity: Vote::class)]
    private Collection $votes;

    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setOption_id($this);
        }
        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            if ($vote->getOption_id() === $this) {
                $vote->setOption_id(null);
            }
        }
        return $this;
    }
}
