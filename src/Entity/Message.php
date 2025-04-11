<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MessageRepository;

use App\Entity\User;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Chat::class, inversedBy: "messages")]
    #[ORM\JoinColumn(name: 'chat_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Chat $chat_id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "messages")]
    #[ORM\JoinColumn(name: 'posted_by', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private User $posted_by;

    #[ORM\Column(type: "text")]
    private string $contenu;

    #[ORM\Column(type: "string")]
    private string $type;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $post_time;

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

    public function getPosted_by()
    {
        return $this->posted_by;
    }

    public function setPosted_by($value)
    {
        $this->posted_by = $value;
    }

    public function getContenu()
    {
        return $this->contenu;
    }

    public function setContenu($value)
    {
        $this->contenu = $value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($value)
    {
        $this->type = $value;
    }

    public function getPost_time()
    {
        return $this->post_time;
    }

    public function setPost_time($value)
    {
        $this->post_time = $value;
    }
}
