<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Technologies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le nom de la technologie est requis.")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $nom_tech;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le type de technologie est requis.")]

    private string $type_tech;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "La complexité est requise.")]
  
    private string $complexite;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "La documentation est requise.")]
    #[Assert\Url(message: "L'URL de documentation n'est pas valide.")]
    private string $documentaire;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "La compatibilité est requise.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "La compatibilité ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $compatibilite;

    /**
     * @var Collection<int, Projets>
     */
    #[ORM\ManyToMany(targetEntity: Projets::class, mappedBy: 'technologies')]
    private Collection $projets;

    public function __construct()
    {
        $this->projets = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getNomTech()
    {
        return $this->nom_tech;
    }

    public function setNomTech($value)
    {
        $this->nom_tech = $value;
    }

    public function getTypeTech()
{
    return $this->type_tech;
}


public function setTypeTech($value)
{
    $this->type_tech = $value;
}

    public function getComplexite()
    {
        return $this->complexite;
    }

    public function setComplexite($value)
    {
        $this->complexite = $value;
    }

    public function getDocumentaire()
    {
        return $this->documentaire;
    }

    public function setDocumentaire($value)
    {
        $this->documentaire = $value;
    }

    public function getCompatibilite()
    {
        return $this->compatibilite;
    }

    public function setCompatibilite($value)
    {
        $this->compatibilite = $value;
    }

    /**
     * @return Collection<int, Projets>
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function addProjet(Projets $projet): static
    {
        if (!$this->projets->contains($projet)) {
            $this->projets->add($projet);
            $projet->addTechnology($this);
        }

        return $this;
    }

    public function removeProjet(Projets $projet): static
    {
        if ($this->projets->removeElement($projet)) {
            $projet->removeTechnology($this);
        }

        return $this;
    }


      
    
    
}
