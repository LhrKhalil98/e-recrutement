<?php

namespace App\Entity;

use App\Repository\CategorieOffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieOffreRepository::class)
 */
class CategorieOffre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $cat_description;

    /**
     * @ORM\OneToMany(targetEntity=Offre::class, mappedBy="id_categorie" , cascade={"persist", "remove"})
     */
    private $offres;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $abreviation;

    public function __construct()
    {
        $this->offres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatDescription(): ?string
    {
        return $this->cat_description;
    }

    public function setCatDescription(string $cat_description): self
    {
        $this->cat_description = $cat_description;

        return $this;
    }

    /**
     * @return Collection|Offre[]
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setIdCategorie($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getIdCategorie() === $this) {
                $offre->setIdCategorie(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return $this->id;
    }

    public function getAbreviation(): ?string
    {
        return $this->abreviation;
    }

    public function setAbreviation(string $abreviation): self
    {
        $this->abreviation = $abreviation;

        return $this;
    }
}
