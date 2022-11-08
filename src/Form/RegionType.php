<?php

namespace App\Form;

use App\Entity\Pays;
use App\Entity\Region;
use App\Repository\PaysRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('region' , TextType::class ,[
                'label' =>'Region ',
                'attr' => ['class' => 'form-control '  ]

            ])
            ->add('pays', EntityType::class , [
                'class'=>Pays::class,
                'query_builder' => function (PaysRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.region is NULL');
                },
                'placeholder'=>  'Select contry',
                'multiple'=> 'true' , 
                'attr' => ['class' => 'form-control '  ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Region::class,
        ]);
    }
}
