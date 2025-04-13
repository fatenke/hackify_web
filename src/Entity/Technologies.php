<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Projets;

#[ORM\Entity]
class Technologies
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $nom_tech;

    #[ORM\Column(type: "string", length: 255)]
    private string $type_tech;

    #[ORM\Column(type: "string", length: 255)]
    private string $complexite;

    #[ORM\Column(type: "string", length: 255)]
    private string $documentaire;

    #[ORM\Column(type: "string", length: 255)]
    private string $compatibilite;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getNom_tech()
    {
        return $this->nom_tech;
    }

    public function setNom_tech($value)
    {
        $this->nom_tech = $value;
    }

    public function getType_tech()
    {
        return $this->type_tech;
    }

    public function setType_tech($value)
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

    #[ORM\OneToMany(mappedBy: "technologie_id", targetEntity: Projets::class)]
    private Collection $projetss;

        public function getProjetss(): Collection
        {
            return $this->projetss;
        }
    
        public function addProjets(Projets $projets): self
        {
            if (!$this->projetss->contains($projets)) {
                $this->projetss[] = $projets;
                $projets->setTechnologie_id($this);
            }
    
            return $this;
        }
    
        public function removeProjets(Projets $projets): self
        {
            if ($this->projetss->removeElement($projets)) {
                // set the owning side to null (unless already changed)
                if ($projets->getTechnologie_id() === $this) {
                    $projets->setTechnologie_id(null);
                }
            }
    
            return $this;
        }
}
