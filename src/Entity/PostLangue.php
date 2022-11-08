<?php

namespace App\Entity;

use App\Repository\PostLangueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostLangueRepository::class)
 */
class PostLangue
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Langue::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_langue;

    /**
     * @ORM\ManyToOne(targetEntity=Poste::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_post;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $description;

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

    public function getIdPost(): ?Poste
    {
        return $this->id_post;
    }

    public function setIdPost(?Poste $id_post): self
    {
        $this->id_post = $id_post;

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
    public function __toString() {
        return $this->id;
    }
}
