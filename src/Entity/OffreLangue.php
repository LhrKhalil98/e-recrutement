<?php

namespace App\Entity;

use App\Repository\OffreLangueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OffreLangueRepository::class)
 */
class OffreLangue
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Langue::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $id_langue;

    /**
     * @ORM\ManyToOne(targetEntity=Offre::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $ref_offre;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text" , nullable=true)
     */
    private $mission;

   



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdLangue(): ?Langue
    {
        return $this->id_langue;
    }

    public function setIdLangue(?Langue $id_langue): self
    {
        $this->id_langue = $id_langue;

        return $this;
    }

    public function getRefOffre(): ?Offre
    {
        return $this->ref_offre;
    }

    public function setRefOffre(?Offre $ref_offre): self
    {
        $this->ref_offre = $ref_offre;

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

    public function getMission(): ?string
    {
        return $this->mission;
    }

    public function setMission(string $mission): self
    {
        $this->mission = $mission;

        return $this;
    }
    public function __toString() {
        return $this->id;
    }

 

  
}
