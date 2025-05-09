<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Entity\User;
use App\Entity\Chat;

#[ORM\Entity]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

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

    #[ORM\OneToMany(mappedBy: 'message', targetEntity: Reaction::class, cascade: ['persist', 'remove'])]
    private Collection $reactions;

    public function __construct()
    {
        $this->reactions = new ArrayCollection();
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

    /**
     * @return Collection<int, Reaction>
     */
    public function getReactions(): Collection
    {
        return $this->reactions;
    }

    public function addReaction(Reaction $reaction): self
    {
        if (!$this->reactions->contains($reaction)) {
            $this->reactions->add($reaction);
            $reaction->setMessage($this);
        }

        return $this;
    }

    public function removeReaction(Reaction $reaction): self
    {
        if ($this->reactions->removeElement($reaction)) {
            // set the owning side to null (unless already changed)
            if ($reaction->getMessage() === $this) {
                // Handle removal in database instead
            }
        }

        return $this;
    }

    public function getAuthorForIndexing(): string
    {
        return $this->posted_by ? $this->posted_by->getNomUser() : '';
    }

    /**
     * Returns the community ID for indexing
     *
     * @return int|null
     * @Groups({"search"})
     */
    public function getCommunityForIndexing(): ?int
    {
        return $this->chat_id ? $this->chat_id->getCommunaute_id()->getId() : null;
    }
}