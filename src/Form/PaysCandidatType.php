<?php

namespace App\Form;

use App\Entity\Pays;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PaysCandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pays' , EntityType::class , [
                'class'=>Pays::class,
                'label' => "Votre Pays*" ,
                'placeholder'=>  'select your country ' , 
                'attr' => ['class' => 'form-control'  ]
            ])

        ;   
    
    }

}
