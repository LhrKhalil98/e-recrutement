<?php 


namespace App\Form  ;

use App\Data\SearchOffreData;
use App\Entity\CategorieOffre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SearchOfferType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    { 
        $builder 
      
            ->add ('categorie' , EntityType::class , [
                'class' =>CategorieOffre::class , 
                'choice_label' => 'cat_description',
                'required' => false  , 
                'label' =>'Categorie' ,
                'placeholder'=>'All' ,
                'attr' => ['class' => 'form-control' ,  ]

            ])    
            ->add ('etat' ,ChoiceType::class , [
                'label' => 'Etat' ,
                'choices'=> [
                    'En Cours' => 'en cours', 
                    'Archive' => 'archive',
                    'Cloturer' => 'Cloturer' 
                ] ,  
                'required' => false  , 
                'placeholder'=>'All' , 
                'attr' => ['class' => 'form-control'  ]
            ])
            ->add ('status' , ChoiceType::class , [
                'required' => false  , 
                'label'=> 'Status' , 

                'choices'=> [
                    'All' => null ,
                    'Off' => 'false' , 
                    'On' => true ,
                  
                ] ,
                'attr' => ['class' => 'form-control' , 'placeholder'=>'Status'  ]

            ])  
            ->add ('annee' , ChoiceType::class , [
                'required' => false  , 
                'label'=> 'Année' , 
                'placeholder'=>'All' , 

                'choices'=> [
                    '2021' => '2021' , 
                    '2022' => '2022' ,
                    '2023' => '2023' ,
                    '2024' => '2024' ,
                    '2025' => '2025' ,
                ] ,
                'attr' => ['class' => 'form-control' , 'placeholder'=>'Chercher'  ]

            ])  
            ->add ('debut' ,DateType::class , [
                'required' => false  , 
                'label' => 'Date debut' ,
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'  ]

            ])  
            ->add ('fin' ,DateType::class , [
                'required' => false  , 
                'label' => 'Date fin' ,
                'widget' => 'single_text',
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day' ],
                'attr' => ['class' => 'form-control' ]

            ])  
            
            ->add ('ref' , TextType::class , [
                'required' => false  , 
                'label'=> 'Intitule', 
                'attr' => ['class' => 'form-control' ,  ]

            ])  
          
            ; 
         
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=> SearchOffreData :: class , 
            'method' => 'GET' , 
            'csrf_protection' => false  
        ]); 

    }
    public function getBlockPrefix()
    {
        return '' ; 
    }


}