<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Entity\Chapitres;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Ressources
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;
    

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le titre est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le titre doit faire au moins {{ limit }} caractères.",
        maxMessage: "Le titre ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $titre;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le type est requis.")]
    private string $type;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    private string $description;

    #[ORM\Column(name: "date_ajout", type: "datetime")]
    #[Assert\NotNull(message: "La date d’ajout est requise.")]
    #[Assert\Type(type: \DateTimeInterface::class, message: "Format de date invalide.")]

    private \DateTimeInterface $dateAjout;

    #[ORM\Column(type: "boolean")]
    #[Assert\Type(type: 'bool', message: 'Valeur invalide pour la validation.')]
    private bool $valide;

    #[ORM\OneToMany(mappedBy: "id_ressources", targetEntity: Chapitres::class)]
    private Collection $chapitres ;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function setTitre($value)
    {
        $this->titre = $value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($value)
    {
        $this->type = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getDateAjout()
    {
        return $this->dateAjout;
    }

    public function setDateAjout($value)
    {
        $this->dateAjout = $value;
    }

    public function getValide()
    {
        return $this->valide;
    }

    public function setValide($value)
    {
        $this->valide = $value;
    }

    public function getChapitres(): Collection
    {
        return $this->chapitres;
    }

    public function addChapitres(Chapitres $chapitres): self
    {
        if (!$this->chapitres->contains($chapitres)) {
            $this->chapitres[] = $chapitres;
            $chapitres->setId_ressources($this);
        }

        return $this;
    }

    public function removeChapitres(Chapitres $chapitres): self
    {
        if ($this->chapitres->removeElement($chapitres)) {
            if ($chapitres->getId_ressources() === $this) {
                $chapitres->setId_ressources(null);
            }
        }

        return $this;
    }
}
