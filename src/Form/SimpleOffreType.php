<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SimpleOffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
        ->add('date_debut' , DateType::class ,[
            'widget' => 'single_text',
            'attr' => ['class' => 'form-control'  ]
            
        ] )
        ->add('date_fin' , DateType::class,[
            'widget' => 'single_text',
            'attr' => ['class' => 'form-control'  ]
            
        ] )
         
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
