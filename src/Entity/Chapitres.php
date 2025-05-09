<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Ressources;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Chapitres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Ressources::class, inversedBy: "chapitres")]
    #[ORM\JoinColumn(name: 'id_ressources', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Ressources $id_ressources;

    #[ORM\Column(type: "string", length: 500)]
    #[Assert\NotBlank(message: "L'URL du fichier est obligatoire.")]
    #[Assert\Url(message: "L'URL doit être valide.")]
    private string $urlFichier;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le titre est requis.")]
    private string $titre;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "Le contenu ne peut pas être vide.")]
    private string $contenu;

    #[ORM\Column(type: "string", length: 50)]
    #[Assert\NotBlank(message: "Le format de fichier est requis.")]
    private string $formatFichier;

    // --- Getters & Setters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRessources(): Ressources
    {
        return $this->id_ressources;
    }

    public function setIdRessources(Ressources $ressource): self
    {
        $this->id_ressources = $ressource;
        return $this;
    }

    public function getUrlFichier(): string
    {
        return $this->urlFichier;
    }

    public function setUrlFichier(string $value): self
    {
        $this->urlFichier = $value;
        return $this;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $value): self
    {
        $this->titre = $value;
        return $this;
    }

    public function getContenu(): string
    {
        return $this->contenu;
    }

    public function setContenu(string $value): self
    {
        $this->contenu = $value;
        return $this;
    }

    public function getFormatFichier(): string
    {
        return $this->formatFichier;
    }

    public function setFormatFichier(string $value): self
    {
        $this->formatFichier = $value;
        return $this;
    }
}
