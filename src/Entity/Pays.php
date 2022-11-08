<?php

namespace App\Entity;

use App\Repository\PaysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaysRepository::class)
 */
class Pays
{
    /**
     * @ORM\Id
     * @ORM\Column( name="id_pays" ,type="string", length=45)
     */
    private $id_pays;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="pays", cascade={"persist", "remove"})
     * @ORM\JoinColumn( referencedColumnName="id_region" , nullable=true)
     */
    private $region;

    /**
     * @ORM\OneToMany(targetEntity=Candidat::class, mappedBy="pays", cascade={"persist", "remove"})
     */
    private $candidats;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $code;

    public function __construct()
    {
        $this->candidats = new ArrayCollection();
    }

    public function getIdPays(): ?string
    {
        return $this->id_pays;
    }
    
    public function setIdPays(string $id_pays): self
    {
        $this->id_pays = $id_pays;

        return $this;
    }
    public function __toString() {
        return $this->id_pays;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection|Candidat[]
     */
    public function getCandidats(): Collection
    {
        return $this->candidats;
    }

    public function addCandidat(Candidat $candidat): self
    {
        if (!$this->candidats->contains($candidat)) {
            $this->candidats[] = $candidat;
            $candidat->setPays($this);
        }

        return $this;
    }

    public function removeCandidat(Candidat $candidat): self
    {
        if ($this->candidats->removeElement($candidat)) {
            // set the owning side to null (unless already changed)
            if ($candidat->getPays() === $this) {
                $candidat->setPays(null);
            }
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }
}
