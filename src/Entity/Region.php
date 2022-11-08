<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 */
class Region
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id_region;

    /**
     * @ORM\OneToMany(targetEntity=Pays::class, mappedBy="region", cascade={"persist", "remove"})
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $region;

    public function __construct()
    {
        $this->pays = new ArrayCollection();
    }

    public function getIdRegion(): ?int
    {
        return $this->id_region;
    }
  
    public function __toString() {
        return $this->id_region;
    }

    /**
     * @return Collection|Pays[]
     */
    public function getPays(): Collection
    {
        return $this->pays;
    }

    public function addPay(Pays $pay): self
    {
        if (!$this->pays->contains($pay)) {
            $this->pays[] = $pay;
            $pay->setRegion($this);
        }

        return $this;
    }

    public function removePay(Pays $pay): self
    {
        if ($this->pays->removeElement($pay)) {
            // set the owning side to null (unless already changed)
            if ($pay->getRegion() === $this) {
                $pay->setRegion(null);
            }
        }

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }
}
