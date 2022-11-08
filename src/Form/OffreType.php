<?php

namespace App\Form;

use App\Entity\Offre;
use App\Entity\Poste;
use App\Entity\Region;
use App\Entity\PostLangue;
use App\Entity\OffreLangue;
use App\Entity\PieceJointe;
use App\Entity\CategorieOffre;
use App\Repository\RegionRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options )
    {
        $builder
              ->add('description', TextType::class, [
                'attr' => ['class' => 'form-control'  ]
            ])
            ->add('id_categorie' , EntityType::class, [
                'class' =>CategorieOffre::class , 
                'choice_label' => 'cat_description',
                'placeholder' => 'Select Categorie',

                'label' => 'Categorie' ,
                'attr' => ['class' => 'form-control'  ]

              
          ])
            ->add('posts', EntityType::class, [
                'class' => Poste::class ,
                'choice_label' => 'description',
                'multiple' => true ,
                'label'=> 'Postes' , 

                'attr' => ['class' => 'form-control'  ]
          ] )
       

            ->add('description', CKEditorType::class, [
                
                'attr' => ['class' => 'form-control'  ]

            ])
            ->add('lieu', TextType::class, [
                'attr' => ['class' => 'form-control'  ]
            ])
            ->add('societe', TextType::class, [
                'label'=>'Société',
                'attr' => ['class' => 'form-control'  ]
            ])
            ->add('intitule_offre', TextType::class, [
                'label' => 'Intitulé offre',
                'attr' => ['class' => 'form-control'  ]
            ])
           
            
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

  
}
