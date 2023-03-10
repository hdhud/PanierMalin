<?php

namespace App\Entity;

use App\Repository\ListeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListeRepository::class)]
class Liste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomListe = null;

    #[ORM\Column(length: 255)]
    private ?string $dateCreation = null;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class, inversedBy: 'listes')]
    private Collection $creePar;

    #[ORM\OneToMany(mappedBy: 'idListe', targetEntity: Compose::class)]
    private Collection $composes;

    public function __construct()
    {
        $this->creePar = new ArrayCollection();
        $this->composes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomListe(): ?string
    {
        return $this->nomListe;
    }

    public function setNomListe(string $nomListe): self
    {
        $this->nomListe = $nomListe;

        return $this;
    }

    public function getDateCreation(): ?string
    {
        return $this->dateCreation;
    }

    public function setDateCreation(string $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getCreePar(): Collection
    {
        return $this->creePar;
    }

    public function addCreePar(Utilisateur $creePar): self
    {
        if (!$this->creePar->contains($creePar)) {
            $this->creePar->add($creePar);
        }

        return $this;
    }

    public function removeCreePar(Utilisateur $creePar): self
    {
        $this->creePar->removeElement($creePar);

        return $this;
    }

    /**
     * @return Collection<int, Compose>
     */
    public function getComposes(): Collection
    {
        return $this->composes;
    }

    public function addCompose(Compose $compose): self
    {
        if (!$this->composes->contains($compose)) {
            $this->composes->add($compose);
            $compose->setidListe($this);
        }

        return $this;
    }

    public function removeCompose(Compose $compose): self
    {
        if ($this->composes->removeElement($compose)) {
            // set the owning side to null (unless already changed)
            if ($compose->getidListe() === $this) {
                $compose->setidListe(null);
            }
        }

        return $this;
    }
}
