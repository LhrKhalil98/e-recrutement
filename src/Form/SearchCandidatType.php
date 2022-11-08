<?php 


namespace App\Form  ;

use App\Entity\Pays;
use App\Data\SearchOffreData;
use App\Entity\CategorieOffre;
use App\Data\SearchCandidatData;
use App\Entity\Poste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SearchCandidatType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    { 
        $builder 
      
          ->add ('pays' , EntityType::class , [
                'class' =>Pays::class , 
                'required' => false  , 
                'label' =>'Pays' ,
                'placeholder'=>'All' , 
                'attr' => ['class' => 'form-control' ,      'placeholder'=>'All' ,
                ]

            ])    
          ->add ('poste' , EntityType::class , [
                'class' =>Poste::class , 
                'required' => false  , 
                'choice_label' => 'description',
                'label' =>'Poste' ,
                'placeholder'=>'All' , 
                'attr' => ['class' => 'form-control' ,'placeholder'=>'All' ,
                ]

            ])    
            ->add ('genre' ,ChoiceType::class , [
                'label' => 'Genre' ,
                'choices'=> [
                    'Homme' => 'Homme', 
                    'Femme' => 'Femme',
                ] ,  
                'required' => false  , 
                'placeholder'=>'All' , 
                'attr' => ['class' => 'form-control'  ]
            ])
    
            
            ->add ('ref' , TextType::class , [
                'required' => false  , 
                'label'=> 'Ref Candidat',
                'attr' => ['class' => 'form-control' ,'placeholder'=>'0000/00' ,   ]
            ])  
            
            ->add ('email' , TextType::class , [
                'required' => false  , 
                'label'=> 'Email ',
                'attr' => ['class' => 'form-control' ,'placeholder'=>'exemple@gmail.com' ,    ]
            ])  
            ->add ('min' , TextType::class , [
                'required' => false  , 
                'label'=> 'Age minimum  ',
                'attr' => ['class' => 'form-control' ,'placeholder'=>'00' ,    ]
            ])  
            ->add ('max' , TextType::class , [
                'required' => false  , 
                'label'=> 'age maximum',
                'attr' => ['class' => 'form-control' ,  'placeholder'=>'00' ,   ]
            ])  
            ; 
         
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=> SearchCandidatData:: class , 
            'method' => 'GET' , 
            'csrf_protection' => false  
        ]); 

    }
    public function getBlockPrefix()
    {
        return '' ; 
    }


}