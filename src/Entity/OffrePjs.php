<?php

namespace App\Entity;

use App\Repository\OffrePjsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OffrePjsRepository::class)
 */
class OffrePjs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Offre::class , cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ref_offre;

    /**
     * @ORM\ManyToOne(targetEntity=PieceJointe::class , cascade={"persist"})
     * @ORM\JoinColumn( referencedColumnName="id_piece_jointe" , nullable=false)
     */
    private $id_psj;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class , cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="id_region" ,nullable=false)
     */
    private $region;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Isrequired;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdPsj(): ?PieceJointe
    {
        return $this->id_psj;
    }

    public function setIdPsj(?PieceJointe $id_psj): self
    {
        $this->id_psj = $id_psj;

        return $this;
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
    public function __toString() {
        return $this->id;
    }

    public function getIsrequired(): ?bool
    {
        return $this->Isrequired;
    }

    public function setIsrequired(bool $Isrequired): self
    {
        $this->Isrequired = $Isrequired;

        return $this;
    }
}
