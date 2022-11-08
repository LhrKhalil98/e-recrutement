<?php

namespace App\Form;

use App\Entity\Pays;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CauseRejetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cause_rejet' , TextareaType::class, [
                'label'=> 'Cause de rejet' , 

                'attr' => ['class' => 'form-control'  ]
            ]) 

        ;   
    
    }

}
