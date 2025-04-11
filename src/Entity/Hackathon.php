<?php

namespace App\Entity;

use App\Repository\HackathonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HackathonRepository::class)]
class Hackathon
{
    // Change the field name to 'id' and make it the primary key.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]  // Use 'id' as the field name and make it the primary key.
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }
    public function __toString(): string
    {
        return (string) $this->id;
    }
}
