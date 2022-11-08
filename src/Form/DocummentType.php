<?php

namespace App\Form;


use App\Entity\Region;

use App\Entity\PieceJointe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class DocummentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options )
    {
        $builder

            
            ->add('region', EntityType::class, [
                'class' => Region::class ,  
                'placeholder' => 'Select Region',
                'choice_label' => 'region',

                'attr' => ['class' => 'form-control'  ]
             ])  

             ->add('pience_jointe', EntityType::class, [
                'class' => PieceJointe::class ,    
                'choice_label' => 'piece_jointe',
                'label'=> 'Document' , 

                'placeholder' => 'Select Documment',

                'attr' => ['class' => 'form-control'  ]
             ])  
             ->add('Isrequired' ,CheckboxType::class , [
                 'label'=> 'Obligatoire',
                 'required'=> false ,
                'attr' => ['class' => 'form-control'  ]

                
             ])  

        
        ;

    }

  
}
