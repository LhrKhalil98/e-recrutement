<?php

namespace App\Form;

use App\Entity\OffreLangue;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          
        ->add('description', CKEditorType::class, [
            'attr' => ['class' => 'form-control'  ]
        ])
        ->add('mission', CKEditorType::class,[
            'attr' => ['class' => 'form-control'  ]
        ])
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OffreLangue::class,
        ]);
    }
}
