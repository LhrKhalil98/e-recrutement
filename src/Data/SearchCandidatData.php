<?php 

namespace App\Data ; 

use App\Entity\Pays;
use App\Entity\Poste;

class SearchCandidatData {




    /**
     * @var Pays[]
     */
    public $pays = []   ; 
 
    /**
     * @var Poste[]
     */
    public $poste = []   ; 
 

    /**
     * @var string
     */
    public $genre = '' ;


    /**
     * @var string
     */
    public $email  = '';
    
    /**
     * @var null|string
     */
    public $min  ; 

    /**
     * @var null|string
     */
    public $max   ; 
    
    /**
     * @var string
     */
    public $ref =''  ; 
    
 

    


 
} 
