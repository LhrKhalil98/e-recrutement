<?php

namespace App\Entity;

use App\Repository\CandidaturePjsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=CandidaturePjsRepository::class)
 */
class CandidaturePjs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Candidature::class, inversedBy="candidaturePjs" , cascade={"persist", "remove"} )
     * @ORM\JoinColumn(nullable=true)
     */
    private $candidature_id;

    /**
     * @ORM\ManyToOne(targetEntity=PieceJointe::class , cascade={"persist", "remove"} )
     * @ORM\JoinColumn( referencedColumnName="id_piece_jointe"  ,nullable=true )
     */
    private $id_piece_jointe;

    /**
     * @ORM\Column(type="string", length=255 ,nullable=true)
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"application/pdf", "application/x-pdf"  },
     *     mimeTypesMessage = "Please upload a valid PDF or Word "
     * )
     */
    private $documment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidatureId(): ?Candidature
    {
        return $this->candidature_id;
    }

    public function setCandidatureId(?Candidature $candidature_id): self
    {
        $this->candidature_id = $candidature_id;

        return $this;
    }

    public function getIdPieceJointe(): ?PieceJointe
    {
        return $this->id_piece_jointe;
    }

    public function setIdPieceJointe(?PieceJointe $id_piece_jointe): self
    {
        $this->id_piece_jointe = $id_piece_jointe;

        return $this;
    }

    public function getDocumment(): ?string
    {
        return $this->documment;
    }

    public function setDocumment(string $documment): self
    {
        $this->documment = $documment;

        return $this;
    }
}
