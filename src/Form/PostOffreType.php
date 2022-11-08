<?php

namespace App\Form;

use App\Entity\Offre;
use App\Entity\Poste;
use App\Entity\PostLangue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostOffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
     
        ->add('posts', EntityType::class, [
            'class' => PostLangue::class ,
            'choice_label' => 'description',
            'label'=> 'postes' , 

            'multiple' => true ,
            'attr' => ['class' => 'form-control'  ]
        ] )
        ;
    }

}
