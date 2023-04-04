<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\Expr\Math;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\ManyToMany(targetEntity: Liste::class, mappedBy: 'creePar')]
    private Collection $listes;

    public function __construct()
    {
        $this->listes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->pseudo;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection<int, Liste>
     */
    public function getListes(): Collection
    {
        return $this->listes;
    }

    public function getNumberOfListes(): int
    {
        return $this->listes->count();
    }

    public function getNumberOfArticles(): int
    {
        $int = 0;
        foreach ($this->listes as $liste) {
            foreach ($liste->getArticlesComposes() as $article) {
                $int += $article->getQuantite();
            }
        }

        return $int;
    }

    public function getNumberOfArticlesEstMarque(): int
    {
        $int = 0;
        foreach ($this->listes as $liste) {
            foreach ($liste->getArticlesComposes() as $article) {
                if ($article->isEstMarque()) {
                    $int += $article->getQuantite();
                }
            }
        }

        return $int;
    }

    public function getNumberOfArticlesNonEstMarque(): int
    {
        $int = 0;
        foreach ($this->listes as $liste) {
            foreach ($liste->getArticlesComposes() as $article) {
                if (!$article->isEstMarque()) {
                    $int += $article->getQuantite();
                }
            }
        }

        return $int;
    }

    public function getPrixTotal(): float
    {
        $prixTotal = 0;
        foreach ($this->listes as $liste) {
            foreach ($liste->getArticlesComposes() as $article) {
                $prixTotal += $article->getPrix() * $article->getQuantite();
            }
        }

        return round($prixTotal,2);
    }

    public function getPrixTotalEstMarque(): float
    {
        $prixTotal = 0;
        foreach ($this->listes as $liste) {
            foreach ($liste->getArticlesComposes() as $article) {
                if ($article->isEstMarque()) {
                    $prixTotal += $article->getPrix() * $article->getQuantite();
                }
            }
        }

        return $prixTotal;
    }

    public function getPrixTotalNonEstMarque(): float
    {
        $prixTotal = 0;
        foreach ($this->listes as $liste) {
            foreach ($liste->getArticlesComposes() as $article) {
                if (!$article->isEstMarque()) {
                    $prixTotal += $article->getPrix() * $article->getQuantite();
                }
            }
        }

        return $prixTotal;
    }

    public function getPrixMoyenParListe(): float
    {
        $prixTotal = 0;
        foreach ($this->listes as $liste) {
            foreach ($liste->getArticlesComposes() as $article) {
                $prixTotal += $article->getPrix() * $article->getQuantite();
            }
        }

        return $prixTotal / $this->listes->count();
    }

    public function getPrixMoyenArticleParListe(): float
    {
        $prixTotal = 0;
        $nombreArticles = 0;
        foreach ($this->listes as $liste) {
            foreach ($liste->getArticlesComposes() as $article) {
                $prixTotal += $article->getPrix() * $article->getQuantite();
                $nombreArticles += $article->getQuantite();
            }
        }

        return round($prixTotal / $nombreArticles, 2);
    }

    public function getPrixMaxParListe(): float
    {
        $prixMax = 0;
        foreach ($this->listes as $liste) {
            $prixTotal = 0;
            foreach ($liste->getArticlesComposes() as $article) {
                $prixTotal += $article->getPrix() * $article->getQuantite();
            }
            if ($prixTotal > $prixMax) {
                $prixMax = $prixTotal;
            }
        }

        return $prixMax;
    }

    public function getPrixMinParListe(): float
    {
        $prixMin = $this->getPrixMaxParListe();
        foreach ($this->listes as $liste) {
            $prixTotal = 0;
            foreach ($liste->getArticlesComposes() as $article) {
                $prixTotal += $article->getPrix() * $article->getQuantite();
            }
            if ($prixTotal < $prixMin) {
                $prixMin = $prixTotal;
            }
        }

        return $prixMin;
    }

    public function getPrixMaxArticleParListe(): float
    {
        $prixMax = 0;
        foreach ($this->listes as $liste) {
            foreach ($liste->getArticlesComposes() as $article) {
                if ($article->getPrix() > $prixMax) {
                    $prixMax = $article->getPrix();
                }
            }
        }

        return $prixMax;
    }

    public function getNomPrixMaxArticleParListe(): string
    {
        $prixMax = 0;
        $nomArticle = '';
        foreach ($this->listes as $liste) {
            foreach ($liste->getArticlesComposes() as $article) {
                if ($article->getPrix() > $prixMax) {
                    $prixMax = $article->getPrix();
                    $nomArticle = $article->getNom();
                }
            }
        }

        return $nomArticle;
    }

    public function getPrixMinArticleParListe(): float
    {
        $prixMin = $this->getPrixMaxArticleParListe();
        foreach ($this->listes as $liste) {
            foreach ($liste->getArticlesComposes() as $article) {
                if ($article->getPrix() < $prixMin) {
                    $prixMin = $article->getPrix();
                }
            }
        }

        return $prixMin;
    }

    public function getNomPrixMinArticleParListe(): string
    {
        $prixMin = $this->getPrixMaxArticleParListe();
        $nomArticle = '';
        foreach ($this->listes as $liste) {
            foreach ($liste->getArticlesComposes() as $article) {
                if ($article->getPrix() < $prixMin) {
                    $prixMin = $article->getPrix();
                    $nomArticle = $article->getNom();
                }
            }
        }

        return $nomArticle;
    }

    public function getPrixParTypeArticle(): array
    {
        $prixParTypeArticle = [];
        foreach ($this->listes as $liste) {
            foreach ($liste->getArticlesComposes() as $article) {
                foreach($article->getType() as $type) {
                    if (!array_key_exists($type->getNomType(), $prixParTypeArticle)) {
                        $prixParTypeArticle[$type->getNomType()] = 0;
                    }
                    $prixParTypeArticle[$type->getNomType()] += $article->getPrix() * $article->getQuantite();
                }
            }
        }

        return $prixParTypeArticle;
    }

    public function getListesOrderByMostRecent(): Collection
    {
        $listes = $this->listes->toArray();
        usort($listes, fn (Liste $a, Liste $b) => $b->getDateCreation() <=> $a->getDateCreation());

        return new ArrayCollection($listes);
    }

    public function addListe(Liste $liste): self
    {
        if (!$this->listes->contains($liste)) {
            $this->listes->add($liste);
            $liste->addCreePar($this);
        }

        return $this;
    }

    public function removeListe(Liste $liste): self
    {
        if ($this->listes->removeElement($liste)) {
            $liste->removeCreePar($this);
        }

        return $this;
    }
}
