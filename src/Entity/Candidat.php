<?php

namespace App\Entity;

use App\Repository\CandidatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=CandidatRepository::class)
 */
class Candidat
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
    private $cand_nom;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $cand_prenom;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $cand_email;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $cand_tel;

    /**
     * @ORM\Column(type="date")
     *   * @Assert\LessThan("-18 years" , message = "date naissance   non valid" )
     * )
     */
    private $cand_date_naissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cand_adresse;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $ville;

    /**
     * @ORM\Column(type="integer")
     */
    private $cand_code_postal;
    

    /**
     * @ORM\OneToMany(targetEntity=Candidature::class, mappedBy="cand_id" , cascade={"persist", "remove"})
     */
    private $candidatures;

    /**
     * @ORM\ManyToOne(targetEntity=Pays::class, inversedBy="candidats" , cascade={"persist", "remove"})
     * @ORM\JoinColumn( referencedColumnName="id_pays" ,nullable=false)
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=45 )
     */
    private $cand_tel_whatsapp;

    /**
     * @ORM\Column(type="string", length=255 ,  nullable=true)
     */
    private $etat_civil;

    /**
     * @ORM\Column(type="integer" ,  nullable=true)
     */
    private $nb_enfant;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandNom(): ?string
    {
        return $this->cand_nom;
    }

    public function setCandNom(string $cand_nom): self
    {
        $this->cand_nom = $cand_nom;

        return $this;
    }

    public function getCandPrenom(): ?string
    {
        return $this->cand_prenom;
    }

    public function setCandPrenom(string $cand_prenom): self
    {
        $this->cand_prenom = $cand_prenom;

        return $this;
    }

    public function getCandEmail(): ?string
    {
        return $this->cand_email;
    }

    public function setCandEmail(string $cand_email): self
    {
        $this->cand_email = $cand_email;

        return $this;
    }

    public function getCandTel(): ?int
    {
        return $this->cand_tel;
    }

    public function setCandTel(int $cand_tel): self
    {
        $this->cand_tel = $cand_tel;

        return $this;
    }

    public function getCandDateNaissance(): ?\DateTimeInterface
    {
        return $this->cand_date_naissance;
    }

    public function setCandDateNaissance(\DateTimeInterface $cand_date_naissance): self
    {
        $this->cand_date_naissance = $cand_date_naissance;

        return $this;
    }

    public function getCandAdresse(): ?string
    {
        return $this->cand_adresse;
    }

    public function setCandAdresse(string $cand_adresse): self
    {
        $this->cand_adresse = $cand_adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCandCodePostal(): ?int
    {
        return $this->cand_code_postal;
    }

    public function setCandCodePostal(int $cand_code_postal): self
    {
        $this->cand_code_postal = $cand_code_postal;

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
            $candidature->setCandId($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getCandId() === $this) {
                $candidature->setCandId(null);
            }
        }

        return $this;
    }

    public function getPays(): ?Pays
    {
        return $this->pays;
    }

    public function setPays(?Pays $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getCandTelWhatsapp(): ?string
    {
        return $this->cand_tel_whatsapp;
    }

    public function setCandTelWhatsapp(string $cand_tel_whatsapp): self
    {
        $this->cand_tel_whatsapp = $cand_tel_whatsapp;

        return $this;
    }

    public function getEtatCivil(): ?string
    {
        return $this->etat_civil;
    }

    public function setEtatCivil(string $etat_civil): self
    {
        $this->etat_civil = $etat_civil;

        return $this;
    }

    public function getNbEnfant(): ?int
    {
        return $this->nb_enfant;
    }

    public function setNbEnfant(int $nb_enfant): self
    {
        $this->nb_enfant = $nb_enfant;

        return $this;
    }


    
 

}
