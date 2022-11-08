<?php

namespace App\Form;

use App\Entity\Pays;
use App\Entity\Poste;
use App\Entity\Region;
use App\Entity\Candidat;
use App\Entity\PostLangue;
use App\Repository\OffreRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          
           
        
            ->add('cand_nom', TextType::class,[
                'label' => 'Nom*' , 
                'attr' => ['class' => 'form-control'  ]
            ])
            ->add('sexe' , ChoiceType::class , [
                'label' => ' Genre*' ,
                'choices'=> [
                    'Homme' => 'Homme', 
                    'Femme' => 'Femme'
                ] , 
                'placeholder'=>'genre' , 
                'attr' => ['class' => 'form-control'  ]
            ])
            ->add('cand_prenom' , TextType::class,[
                'label'=> 'Prenom*' , 
                'attr' => ['class' => 'form-control'  ]
            ])
            ->add('cand_email' , EmailType::class , [
                'label' =>'Email*' , 
                'attr' => ['class' => 'form-control'  ]
            ])
            ->add('cand_tel' , TelType::class ,[
                'label' =>'Numero Telephone*' , 
                'attr' => ['class' => 'form-control'  ]
            ] )
            ->add('cand_tel_whatsapp' , TelType::class , [
                'label' => 'Numero Whatsapp*' , 
                'attr' => ['class' => 'form-control'  ]
            ])

            ->add('cand_date_naissance' , BirthdayType::class, [
                'years' => range(1960,2020) , 
                'widget' => 'single_text',
                'label' =>'Date de naissance*' , 
                'attr' => ['class' => 'form-control'  ]
            ])
            ->add('cand_adresse' , TextType::class , [
                'label' => 'Adresse*' , 
                'attr' => ['class' => 'form-control'  ]

            ])
            ->add('etat_civil' , ChoiceType::class , [
                'label' => ' Etat civil*' ,
                'choices'=> [
                    'Célibataire' => 'Célibataire', 
                    'Marié' => 'Marié' , 
                    'Divorcé' => 'Divorcé' ,
                    'Veuf' => 'Veuf' , 

                ] , 
                'placeholder'=>'Etat civil' , 
                'attr' => ['class' => 'form-control'  ]
            ])
            ->add('nb_enfant' , TextType::class , [
                'label' => 'Nombre enfants' , 
                'empty_data' => 0,
                'attr' => ['class' => 'form-control'  ]

            ])
       
            ->add('ville' , TextType::class ,[
                'label' => 'Ville*' , 
                'attr' => ['class' => 'form-control'  ]

            ])
            ->add('cand_code_postal' , NumberType::class , [
                'label'=> 'Code Postal*' ,
                'attr' => ['class' => 'form-control'  ]

            ])
        ;   
    
    }


}
