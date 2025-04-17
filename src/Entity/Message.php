<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Chat::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(name: 'chat_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?Chat $chat_id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(name: 'posted_by', referencedColumnName: 'id_user', onDelete: 'CASCADE')]
    private ?User $posted_by = null;

    #[ORM\Column(type: 'text')]
    private string $contenu;

    #[ORM\Column(type: 'string')]
    private string $type;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $post_time;

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

    public function getPosted_by(): ?User
    {
        return $this->posted_by;
    }

    public function setPosted_by(?User $value): self
    {
        $this->posted_by = $value;
        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $value): self
    {
        $this->contenu = $value;
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

    public function getPost_time(): ?\DateTimeInterface
    {
        return $this->post_time;
    }

    public function setPost_time(\DateTimeInterface $value): self
    {
        $this->post_time = $value;
        return $this;
    }
}