<?php

namespace App\Entity;

use App\Entity\PostLangue;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PosteRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=PosteRepository::class)
 */
class Poste
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date_creation;

    /**
     * @ORM\ManyToMany(targetEntity=Offre::class, mappedBy="posts")
     */
    private $offres;

    /**
     * @ORM\OneToMany(targetEntity=Candidature::class, mappedBy="poste", orphanRemoval=true)
     */
    private $candidatures;

    /**
     * @ORM\OneToMany(targetEntity=PostLangue::class, mappedBy="id_post", orphanRemoval=true)
     */
    private $langues;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    public function __construct()
    {
        $this->offres = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

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
            $offre->addPost($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            $offre->removePost($this);
        }

        return $this;
    }
    public function __toString() {
        return $this->id;
    }

    /**
     * @return Collection|Candidature[]
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures[] = $candidature;
            $candidature->setPoste($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getPoste() === $this) {
                $candidature->setPoste(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection|PostLangue[]
     */
    public function getLangues(): Collection
    {
        return $this->langues;
    }

    public function addLangue(PostLangue $langue): self
    {
        if (!$this->langues->contains($langue)) {
            $this->langues[] = $langue;
            $langue->setIdPost($this);
        }

        return $this;
    }

    public function removeLangue(PostLangue $langue): self
    {
        if ($this->langues->removeElement($langue)) {
            // set the owning side to null (unless already changed)
            if ($langue->getIdPost() === $this) {
                $langue->setIdPost(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
