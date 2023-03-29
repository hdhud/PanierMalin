<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomArticle = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\ManyToMany(targetEntity: TypeArticle::class, inversedBy: 'articles')]
    private Collection $type;

    #[ORM\OneToMany(mappedBy: 'idArticle', targetEntity: Compose::class)]
    private Collection $composes;

    
    public function __construct()
    {
        $this->type = new ArrayCollection();
        $this->composes = new ArrayCollection();
    }
    
    public function __toString()
    {
        return $this->nomArticle;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomArticle(): ?string
    {
        return $this->nomArticle;
    }

    public function setNomArticle(string $nomArticle): self
    {
        $this->nomArticle = $nomArticle;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, TypeArticle>
     */
    public function getType(): Collection
    {
        return $this->type;
    }

    public function addType(TypeArticle $type): self
    {
        if (!$this->type->contains($type)) {
            $this->type->add($type);
        }

        return $this;
    }

    public function removeType(TypeArticle $type): self
    {
        $this->type->removeElement($type);

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
            $compose->setidArticle($this);
        }

        return $this;
    }

    public function removeCompose(Compose $compose): self
    {
        if ($this->composes->removeElement($compose)) {
            // set the owning side to null (unless already changed)
            if ($compose->getidArticle() === $this) {
                $compose->setidArticle(null);
            }
        }

        return $this;
    }
}
