<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class PollVote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Poll::class, inversedBy: 'poll_votes')]
    #[ORM\JoinColumn(name: 'poll_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?Poll $poll_id = null;

    #[ORM\ManyToOne(targetEntity: PollOption::class, inversedBy: 'poll_votes')]
    #[ORM\JoinColumn(name: 'option_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?PollOption $option_id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'poll_votes')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id_user', onDelete: 'CASCADE')]
    private ?User $user_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $value): self
    {
        $this->id = $value;
        return $this;
    }

    public function getPoll_id(): ?Poll
    {
        return $this->poll_id;
    }

    public function setPoll_id(?Poll $value): self
    {
        $this->poll_id = $value;
        return $this;
    }

    public function getOption_id(): ?PollOption
    {
        return $this->option_id;
    }

    public function setOption_id(?PollOption $value): self
    {
        $this->option_id = $value;
        return $this;
    }

    public function getUser_id(): ?User
    {
        return $this->user_id;
    }

    public function setUser_id(?User $value): self
    {
        $this->user_id = $value;
        return $this;
    }
}