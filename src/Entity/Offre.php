<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OffreRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OffreRepository::class)
 */
class Offre
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
    private $date_debut;

    /**
     * @ORM\Column(type="date")
     */
    private $date_fin;

    /**
     * @ORM\Column(type="date")
     */
    private $date_creation;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieOffre::class, inversedBy="offres" , cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_categorie;

    /**
     * @ORM\ManyToMany(targetEntity=Poste::class, inversedBy="offres" , cascade={"persist", "remove"})
     */
    private $posts;
  

    /**
     * @ORM\OneToMany(targetEntity=Candidature::class, mappedBy="ref_offre" , cascade={"persist", "remove"})
     */
    private $candidatures;

  
    /**
     * @ORM\OneToMany(targetEntity=OffreLangue::class,  mappedBy="ref_offre" , cascade={"persist", "remove"})
     */
    private $details;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $intitule_offre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $REF;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $societe;

    /**
     * @ORM\Column(type="integer" ,  nullable=true)
     */
    private $nb_candidats;

  


    public function __construct()
    {

        $this->posts = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
        
      
    }

    public function getId(): ?int
    {
        return $this->id;
    }
   
  

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
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

    public function getIdCategorie(): ?CategorieOffre
    {
        return $this->id_categorie;
    }

    public function setIdCategorie(?CategorieOffre $id_categorie): self
    {
        $this->id_categorie = $id_categorie;

        return $this;
    }

    /**
     * @return Collection|Poste[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Poste $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
        }

        return $this;
    }

    public function removePost(Poste $post): self
    {
        $this->posts->removeElement($post);

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
            $candidature->setRefOffre($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getRefOffre() === $this) {
                $candidature->setRefOffre(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection|OffreLangue[]
     */
    public function getDetails(): Collection
    {
        return $this->details;
    }

    public function addDetail(OffreLangue $detail): self
    {
        if (!$this->details->contains($detail)) {
            $this->details[] = $detail;
            $detail->setRefOffre($this);
        }

        return $this;
    }

    public function removeDetail(OffreLangue $detail): self
    {
        if ($this->details->removeElement($detail)) {
            // set the owning side to null (unless already changed)
            if ($detail->getRefOffre() === $this) {
                $detail->setRefOffre(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getIntituleOffre(): ?string
    {
        return $this->intitule_offre;
    }

    public function setIntituleOffre(string $intitule_offre): self
    {
        $this->intitule_offre = $intitule_offre;

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
    
      /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->date_debut < $this->date_fin) {
            $context->buildViolation('La date de debut doit etre superieur à la date de fin ')
                ->atPath('date_debut')
                ->addViolation();
        }
    }

    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(string $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    public function getNbCandidats(): ?int
    {
        return $this->nb_candidats;
    }

    public function setNbCandidats(int $nb_candidats): self
    {
        $this->nb_candidats = $nb_candidats;

        return $this;
    }
}
