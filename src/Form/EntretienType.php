<?php

namespace App\Form;

use App\Entity\Entretien;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EntretienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('responsable' , TextType::class, [
                'attr' => ['class' => 'form-control'  ]
            ])
            ->add('date' , DateTimeType::class , [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control' ,  ]
            ] )
            
            ->add('type', ChoiceType::class , [
                
                'label'=> 'Type' , 
                'choices'=> [
                    'Entrevue' => 'entrevue' , 
                    'Entretien'=>'entretien'
                  
                ] ,
                'attr' => ['class' => 'form-control' ,  ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entretien::class,
        ]);
    }
}
