<?php

namespace App\Entity;

use App\Repository\CandidatureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CandidatureRepository::class)
 */
class Candidature
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
    private $candidature_date;

    /**
     * @ORM\ManyToOne(targetEntity=Candidat::class, inversedBy="candidatures" , cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $cand_id;

    /**
     * @ORM\ManyToOne(targetEntity=Offre::class, inversedBy="candidatures" , cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    public $ref_offre;

    /**
     * @ORM\OneToMany(targetEntity=CandidaturePjs::class, mappedBy="candidature_id" , cascade={"persist", "remove"})
     */
    private $candidaturePjs;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etat;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity=Poste::class, inversedBy="candidatures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $poste;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $REF;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $Favorable;

    /**
     * @ORM\OneToMany(targetEntity=Entretien::class, mappedBy="candidat", orphanRemoval=true)
     */
    private $entretiens;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cause_rejet;

    /**
     * @ORM\Column(type="string", length=20 , nullable=true)
     */
    private $phase_rejet;

    /**
     * @ORM\ManyToMany(targetEntity=LienEntretien::class, mappedBy="candidatures")
     */
    private $lienEntretiens;

    /**
     * @ORM\Column(type="boolean")
     */
    private $accepte;

    public function __construct()
    {
        $this->candidaturePjs = new ArrayCollection();
        $this->candidature_date = new \DateTime('now');
        $this->entretiens = new ArrayCollection();
        $this->lienEntretiens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidatureDate(): ?\DateTimeInterface
    {
        return $this->candidature_date;
    }

    public function setCandidatureDate(\DateTimeInterface $candidature_date): self
    {
        $this->candidature_date = $candidature_date;

        return $this;
    }

    public function getCandId(): ?Candidat
    {
        return $this->cand_id;
    }

    public function setCandId(?Candidat $cand_id): self
    {
        $this->cand_id = $cand_id;

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

    /**
     * @return Collection|CandidaturePjs[]
     */
    public function getCandidaturePjs(): Collection
    {
        return $this->candidaturePjs;
    }

    public function addCandidaturePj(CandidaturePjs $candidaturePj): self
    {
        if (!$this->candidaturePjs->contains($candidaturePj)) {
            $this->candidaturePjs[] = $candidaturePj;
            $candidaturePj->setCandidatureId($this);
        }

        return $this;
    }

    public function removeCandidaturePj(CandidaturePjs $candidaturePj): self
    {
        if ($this->candidaturePjs->removeElement($candidaturePj)) {
            // set the owning side to null (unless already changed)
            if ($candidaturePj->getCandidatureId() === $this) {
                $candidaturePj->setCandidatureId(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return $this->id;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getPoste(): ?Poste
    {
        return $this->poste;
    }

    public function setPoste(?Poste $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    public function getREF(): ?string
    {
        return $this->REF;
    }

    public function setREF(string $REF): self
    {
        $this->REF = $REF;

        return $this;
    }

    public function getFavorable(): ?bool
    {
        return $this->Favorable;
    }

    public function setFavorable(?bool $Favorable): self
    {
        $this->Favorable = $Favorable;

        return $this;
    }

    /**
     * @return Collection|Entretien[]
     */
    public function getEntretiens(): Collection
    {
        return $this->entretiens;
    }

    public function addEntretien(Entretien $entretien): self
    {
        if (!$this->entretiens->contains($entretien)) {
            $this->entretiens[] = $entretien;
            $entretien->setCandidat($this);
        }

        return $this;
    }

    public function removeEntretien(Entretien $entretien): self
    {
        if ($this->entretiens->removeElement($entretien)) {
            // set the owning side to null (unless already changed)
            if ($entretien->getCandidat() === $this) {
                $entretien->setCandidat(null);
            }
        }

        return $this;
    }

    public function getCauseRejet(): ?string
    {
        return $this->cause_rejet;
    }

    public function setCauseRejet(?string $cause_rejet): self
    {
        $this->cause_rejet = $cause_rejet;

        return $this;
    }

    public function getPhaseRejet(): ?string
    {
        return $this->phase_rejet;
    }

    public function setPhaseRejet(string $phase_rejet): self
    {
        $this->phase_rejet = $phase_rejet;

        return $this;
    }

    /**
     * @return Collection|LienEntretien[]
     */
    public function getLienEntretiens(): Collection
    {
        return $this->lienEntretiens;
    }

    public function addLienEntretien(LienEntretien $lienEntretien): self
    {
        if (!$this->lienEntretiens->contains($lienEntretien)) {
            $this->lienEntretiens[] = $lienEntretien;
            $lienEntretien->addCandidature($this);
        }

        return $this;
    }

    public function removeLienEntretien(LienEntretien $lienEntretien): self
    {
        if ($this->lienEntretiens->removeElement($lienEntretien)) {
            $lienEntretien->removeCandidature($this);
        }

        return $this;
    }

    public function getAccepte(): ?bool
    {
        return $this->accepte;
    }

    public function setAccepte(bool $accepte): self
    {
        $this->accepte = $accepte;

        return $this;
    }
}
