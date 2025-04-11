<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VoteRepository;

use App\Entity\User;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
#[ORM\Table(name: 'votes')]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Poll::class, inversedBy: "votes")]
    #[ORM\JoinColumn(name: 'poll_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Poll $poll_id;

    #[ORM\ManyToOne(targetEntity: Poll_option::class, inversedBy: "votes")]
    #[ORM\JoinColumn(name: 'option_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Poll_option $option_id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "votes")]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private User $user_id;

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

    public function getOption_id()
    {
        return $this->option_id;
    }

    public function setOption_id($value)
    {
        $this->option_id = $value;
    }

    public function getUser_id()
    {
        return $this->user_id;
    }

    public function setUser_id($value)
    {
        $this->user_id = $value;
    }
}
