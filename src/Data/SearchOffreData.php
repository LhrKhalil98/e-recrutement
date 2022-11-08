<?php 

namespace App\Data ; 

use App\Entity\CategorieOffre;

class SearchOffreData {




    /**
     * @var CategorieOffre[]
     */
    public $categorie = []   ; 

    /**
     * @var string
     */
    public $etat = '' ;


    /**
     * @var bool
     */
    public $status ;

    /**
     * @var string
     */
    public $annee  = '';
    
    /**
     * @var null|\DateTimeInterface
     */
    public $debut  ; 

    /**
     * @var null|\DateTimeInterface
     */
    public $fin   ; 
    
    /**
     * @var string
     */
    public $ref   ; 


    


 
} 
