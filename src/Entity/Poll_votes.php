<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\User;

#[ORM\Entity]
class Poll_votes
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id;

        #[ORM\ManyToOne(targetEntity: Polls::class, inversedBy: "poll_votess")]
    #[ORM\JoinColumn(name: 'poll_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Polls $poll_id;

        #[ORM\ManyToOne(targetEntity: Poll_options::class, inversedBy: "poll_votess")]
    #[ORM\JoinColumn(name: 'option_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Poll_options $option_id;

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "poll_votess")]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id_user', onDelete: 'CASCADE')]
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
