<?php

namespace App\Entity;

use App\Repository\PieceJointeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PieceJointeRepository::class)
 */
class PieceJointe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id_piece_jointe" ,type="integer")
     */
    private $id_piece_jointe;

    /**
     * @ORM\Column(type="string" , length=45)
     */
    private $piece_jointe;


     

    public function getIdPieceJointe(): ?int
    {
        return $this->id_piece_jointe;
    }
  
    public function __toString() {
        return $this->id_piece_jointe;
    }
    
    public function getPieceJointe(): ?string
    {
        return $this->piece_jointe;
    }

    public function setPieceJointe(string $piece_jointe): self
    {
        $this->piece_jointe = $piece_jointe;

        return $this;
    }

  

}
