<?php

namespace App\Entity;

use App\Repository\ComposeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComposeRepository::class)]
class Compose
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'composes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $idArticle = null;

    #[ORM\ManyToOne(inversedBy: 'composes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Liste $idListe = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?bool $estMarque = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getidArticle(): ?Article
    {
        return $this->idArticle;
    }

    public function setidArticle(?Article $idArticle): self
    {
        $this->idArticle = $idArticle;

        return $this;
    }

    public function getidListe(): ?Liste
    {
        return $this->idListe;
    }

    public function setidListe(?Liste $idListe): self
    {
        $this->idListe = $idListe;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function isEstMarque(): ?bool
    {
        return $this->estMarque;
    }

    public function setEstMarque(bool $estMarque): self
    {
        $this->estMarque = $estMarque;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->idArticle->getPrix();
    }

    public function getNom(): ?string
    {
        return $this->idArticle->getNomArticle();
    }

    /**
     * @return Collection<int, TypeArticle>
     */
    public function getType(): Collection
    {
        return $this->idArticle->getType();
    }
}
