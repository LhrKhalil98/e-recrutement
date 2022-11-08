<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Entity\Offre;
use App\Entity\Poste;
use App\Entity\Candidat;
use App\Entity\OffrePjs;
use App\Entity\Entretien;
use App\Entity\PostLangue;
use App\Form\CandidatType;
use App\Entity\Candidature;
use App\Form\CauseRejetType;
use App\Entity\CandidaturePjs;
use App\Form\PaysCandidatType;
use App\Data\SearchCandidatData;
use App\Form\SearchCandidatType;
use App\Repository\PaysRepository;
use App\Repository\OffreRepository;
use App\Repository\PosteRepository;
use App\Repository\RegionRepository;
use App\Repository\CandidatRepository;
use App\Repository\OffrePjsRepository;
use App\Repository\EntretienRepository;
use Symfony\Component\Form\FormBuilder;
use App\Repository\CandidatureRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @Route("/candidat")
 * @IsGranted("ROLE_ADMIN")
 */
class CandidatController extends AbstractController
{
    
    /**
     * @Route("/candidature", name="candidatures_index", methods={"GET"})
     */
    public function candidatures( Request $request , CandidatureRepository $candidaturesRepository   ): Response
    {

        $data = new SearchCandidatData();
        $formFilter = $this->createForm(SearchCandidatType::class , $data) ; 
        $formFilter->handleRequest($request) ;
        
        $candidatures = $candidaturesRepository->filterCandidat($data) ; 

        return $this->render('candidat/candidatures.html.twig', [
            'candidatures' => $candidatures ,
            'formFilter' =>$formFilter->createView()

        ]);
    }

    /**
     * @Route("/candidature/phase2", name="candidatures_valide", methods={"GET"})
     */
    public function candidaturesValide(Request $request , CandidatureRepository $candidaturesRepository   ): Response
    {
        $data = new SearchCandidatData();
        $formFilter = $this->createForm(SearchCandidatType::class , $data) ; 
        $formFilter->handleRequest($request) ;
        
        $candidatures = $candidaturesRepository->filterCandidat($data) ; 
        return $this->render('candidat/candidaturesvalid.html.twig', [
            'candidatures' => $candidatures ,
            'formFilter' =>$formFilter->createView()
        ]);
       
    }
    /**
     * @Route("/candidature/phase3", name="candidatures_phase3", methods={"GET"})
     */
    public function candidaturesphase3( Request $request , CandidatureRepository $candidaturesRepository  ): Response
    {
        $data = new SearchCandidatData();
        $formFilter = $this->createForm(SearchCandidatType::class , $data) ; 
        $formFilter->handleRequest($request) ;
        
        $candidatures = $candidaturesRepository->filterCandidat($data) ; 
        return $this->render('candidat/candidaturesphase3.html.twig', [
            'candidatures' => $candidatures ,
            'formFilter' =>$formFilter->createView()
        ]);
    }

    /**
     * @Route("/candidature/archive", name="candidatures_archive", methods={"GET"})
     */
    public function candidaturesArchive( Request $request , CandidatureRepository $candidaturesRepository   ): Response
    {
        $data = new SearchCandidatData();
        $formFilter = $this->createForm(SearchCandidatType::class , $data) ; 
        $formFilter->handleRequest($request) ;
        
        $candidatures = $candidaturesRepository->filterCandidat($data) ; 
        return $this->render('candidat/candidaturesrejter.html.twig', [
            'candidatures' => $candidatures ,
            'formFilter' =>$formFilter->createView()
        ]);
    }

       /**
      * @Route("/editcan/{id}", name="candidature_edit", methods={"GET","POST"})
      */
      public function editcand(Request $request  , Candidature $candidat, PaysRepository $pay_rep , OffrePjsRepository $offrePjsRepository ,  OffreRepository $offre_rep , CandidatureRepository $candidatRepository): Response
      {

          $offre = $candidat->getRefOffre();
          $offrePjs =  $offrePjsRepository->findBy(['ref_offre'=>$offre]);
          $pay = $candidat->getCandId()->getPays() ; 



          $id = $offre->getId();
          $offre = $offre_rep -> findOneBy(['id'=> $id] ) ;
 
          $form = $this->createFormBuilder() ; 
          $form  ->add('poste', EntityType::class, [
             'label' => "Post*" ,
             'class' => Poste::class ,
             'choice_label' => 'description',
             'query_builder' => function (PosteRepository $er  ) use ($id) {
 
                 return $er->createQueryBuilder('u')
                     ->join('u.offres','c')
 
                     ->where('c.id IN (:offre)')
                     ->setParameter('offre'  , $id);
 
             },
             'data' =>$candidat->getPoste() , 
             'attr' => ['class' => 'form-control'  ]
         ] )
         ->add('cand_nom', TextType::class,[
            'label' => 'Nom*' , 
            'data' =>$candidat->getCandId()->getCandNom() , 

            'attr' => ['class' => 'form-control'  ]
            
        ])
        ->add('sexe' , ChoiceType::class , [
            'label' => ' Genre*' ,
            'choices'=> [
                'Homme' => 'Homme', 
                'Femme' => 'Femme'
            ] , 
            'placeholder'=>'genre' , 
            'data' =>$candidat->getCandId()->getSexe() , 

            'attr' => ['class' => 'form-control'  ]
        ])
        ->add('cand_prenom' , TextType::class,[
            'label'=> 'Prenom*' , 
            'data' =>$candidat->getCandId()->getCandPrenom() , 

            'attr' => ['class' => 'form-control'  ]
        ])
        ->add('cand_email' , EmailType::class , [
            'label' =>'Email*' , 
            'data' =>$candidat->getCandId()->getCandEmail() , 

            'attr' => ['class' => 'form-control'  ]
        ])
        ->add('cand_tel' , TelType::class ,[
            'label' =>'Telephone*' , 
            'data' =>$candidat->getCandId()->getCandTel(), 

            'attr' => ['class' => 'form-control'  ]
        ] )
        ->add('cand_tel_whatsapp' , TelType::class , [
            'label' => 'Numero Whatsapp*' , 
            'data' =>$candidat->getCandId()->getCandTelWhatsapp() , 

            'attr' => ['class' => 'form-control'  ]
        ])

        ->add('cand_date_naissance' , BirthdayType::class, [
            'years' => range(1960,2020) , 
            'widget' => 'single_text',
            'label' =>'Date de naissance*' , 
            'data' =>$candidat->getCandId()->getCandDateNaissance() , 

            'attr' => ['class' => 'form-control'  ]
        ])
        ->add('cand_adresse' , TextType::class , [
            'label' => 'Adresse*' , 
            'data' =>$candidat->getCandId()->getCandAdresse() , 

            'attr' => ['class' => 'form-control'  ]

        ])
        ->add('ville' , TextType::class ,[
            'label' => 'Ville*' , 
            'data' =>$candidat->getCandId()->getVille() , 

            'attr' => ['class' => 'form-control'  ]

        ])
        ->add('cand_code_postal' , NumberType::class , [
            'label'=> 'Code Postal*' ,
            'data' =>$candidat->getCandId()->getCandCodePostal() , 

            'attr' => ['class' => 'form-control'  ]

        ])
         ; 
       
         $form = $form ->getForm(); 

          $form->handleRequest($request);
          
          if ($form->isSubmitted() && $form->isValid()) {
             $datetime1 = new \DateTime(); 
             $birth = $form->get('cand_date_naissance')->getData() ;
             $age = $datetime1->diff( $birth, true)->y  ;
             if($age < 23 ){
                 $this->addFlash('warning', "date naissance non valid");
 
                 return $this->render('candidat/editcan.html.twig', [
                     'candidat' => $candidat,
                     'form' => $form->createView(),
                 ]);
              }
              $id2 =   $pay->getIdPays() ; 
              $pay =  $pay_rep->findOneBy(['id_pays'=>$id2]);
              $entityManager = $this->getDoctrine()->getManager();
              $candidat1 = $candidat->getCandId() ;
              $candidat1->setPays($pay);
              $candidat1->setCandNom($form->get('cand_nom')->getData());
              $candidat1->setCandPrenom($form->get('cand_prenom')->getData());
              $candidat1->setCandEmail($form->get('cand_email')->getData());
              $candidat1->setSexe($form->get('sexe')->getData());
              $candidat1->setCandTel($form->get('cand_tel')->getData());
              $candidat1->setCandTelWhatsapp($form->get('cand_tel_whatsapp')->getData());
              $candidat1->setCandDateNaissance($form->get('cand_date_naissance')->getData());
              $candidat1->setCandAdresse($form->get('cand_adresse')->getData());
              $candidat1->setVille($form->get('ville')->getData());
              $candidat1->setCandCodePostal($form->get('cand_code_postal')->getData());
                
              $candidat->setPoste($form->get('poste')->getData()) ;
              $id = $offre->getId();
              $offre = $offre_rep -> findOneBy(['id'=> $id] ) ;
              $count = (int) $candidatRepository->countCand(Date('Y'))+1 ; 
            
              $entityManager->flush();
             
             
              return $this->redirectToRoute('candidature_show', ['id'=> $candidat->getId() ]);
 
              
          }
 
          return $this->render('candidat/editcan.html.twig', [
              'candidat' => $candidat,
              'form' => $form->createView(),
          ]);
      }
 
    
    /**
     * @Route("/{id}", name="candidat_show", methods={"GET"})
     */
    public function show(Candidat $candidat ): Response
    {
       
        return $this->render('candidat/show.html.twig', [
            'candidat' => $candidat,
        ]);
    }
    
    /**
     * @Route("/{id}/entretien", name="entretien_candidat_show", methods={"GET"})
     */
    public function entretienCandidatShow(Candidature $candidat ,EntretienRepository $entretien_rep ): Response
    {
        
        $id = $candidat->getId();
        $entretiens = $entretien_rep->findent($id) ;

        return $this->render('candidat/entretiensshow.html.twig', [
            'candidat' => $candidat,
            'entretiens'=>$entretiens,

        ]);
    }

    /**
     * @Route("/candidature/{id}", name="candidature_show", methods={"GET" , "POST"})
     */
    public function showcandidature(Candidature $candidat  , Request $request ,EntretienRepository $entretien_rep): Response
    {


        $rejet = ['message' => 'rejet Form'] ;   
        $form = $this->createForm(CauseRejetType::class, $rejet);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $candidat->setCauseRejet($form->get('cause_rejet')->getData()) ; 
            $candidat->setPhaseRejet($candidat->getEtat()) ; 
            $candidat->setEtat('archive') ; 
            $entityManager->flush();
            return $this->redirectToRoute('candidature_show', ['id'=> $candidat->getId()]);

        }

        $entretiens = $entretien_rep->findBy(['candidat'=>$candidat]) ;
        return $this->render('candidat/candidature_show.html.twig', [
            'candidat' => $candidat,
            'entretiens'=>$entretiens,
            'form' => $form->createView(),

        ]);
    }
   
  

    /**
     * @Route("/{id}/edit", name="candidat_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Candidat $candidat): Response
    {
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('candidat_index');
        }

        return $this->render('candidat/edit.html.twig', [
            'candidat' => $candidat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="candidat_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Candidat $candidat): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($candidat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('candidat_index');
    }

    /**
     * @Route("/candidature/{id}/rejeter", name="candidatures_rejeter", )
     */
    public function rejectercandidature(Request $request, Candidature $candidature): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $candidature->setPhaseRejet($candidature->getEtat()) ; 
            $candidature->setEtat('archive') ; 

            $entityManager->flush();
        

        $this->addFlash('sucesss', "Candidat est archivé ");

        return $this->redirectToRoute('candidature_show', ['id'=> $candidature->getId()]);
    }
    /**
     * @Route("/candidature/{id}/recuperer", name="candidatures_recuperer", methods={"GET"})
     */
    public function recuperercandidature(Request $request, Candidature $candidature): Response
    {
       
            $entityManager = $this->getDoctrine()->getManager();
            $candidature->setEtat($candidature->getPhaseRejet()) ; 
            $candidature->setCauseRejet(Null);
            $entityManager->flush();
        

        return $this->redirectToRoute('candidatures_archive');
    }
    /**
     * @Route("/candidature/{id}/favoris", name="candidatures_favoris", methods={"GET"})
     */
    public function Favoriscandidature(Request $request, Candidature $candidature): Response
    {
       
            $entityManager = $this->getDoctrine()->getManager();
            $candidature->setFavorable(true) ; 
            $entityManager->flush();
        

            $this->addFlash('sucesss', "sucesss");

            return $this->redirectToRoute('candidature_show', ['id'=> $candidature->getId()]);
    }
    
     /**
     * @Route("/candidature/{id}/2emephase", name="candidatures_2eme_phase",)
     */
    public function secondphasecandidature(Request $request, Candidature $candidature , EntretienRepository $entretien_rep): Response
    {
            $entretiens = $entretien_rep->findBy(['candidat'=>$candidature]) ;
            if($entretiens == null ){
                $this->addFlash('warning', " Passer au moins un entretien  ");
                return $this->redirectToRoute('candidature_show', ['id'=> $candidature->getId()]);

            } 
            foreach($entretiens as $entretien) {
                if($entretien->getEtat()== false ) {
                    $this->addFlash('warning', " Il faut  passer tous les entretien  ");
                    return $this->redirectToRoute('candidature_show', ['id'=> $candidature->getId()]);
                }
            }
            $entityManager = $this->getDoctrine()->getManager();
            $candidature->setEtat('phase2') ; 
            $entityManager->flush();
        

        $this->addFlash('sucesss', "sucesss");

        return $this->redirectToRoute('candidature_show', ['id'=> $candidature->getId()]);
    }
    
     /**
     * @Route("/candidature/{id}/3emephase", name="candidatures_3eme_phase",)
     */
    public function thirdphasecandidature(Request $request, Candidature $candidature , EntretienRepository $entretien_rep): Response
    {
            $entretiens = $entretien_rep->findBy(['candidat'=>$candidature]) ;
            if($entretiens == null ){
                $this->addFlash('warning', " Passer au moins un entretien  ");
                return $this->redirectToRoute('candidature_show', ['id'=> $candidature->getId()]);

            } 
            foreach($entretiens as $entretien) {
                if($entretien->getEtat()== false ) {
                    $this->addFlash('warning', " Il faut  passer tous les entretien  ");
                    return $this->redirectToRoute('candidature_show', ['id'=> $candidature->getId()]);
                }
            }
            $entityManager = $this->getDoctrine()->getManager();
            $candidature->setEtat('phase3') ; 
            $entityManager->flush();
        
        $this->addFlash('sucesss', "sucesss");

        return $this->redirectToRoute('candidature_show', ['id'=> $candidature->getId()]);
    }
        /**
     * @Route("/candidature/{id}/accepte", name="candidatures_accepte", methods={"GET"})
     */
    public function aCceptecandidature(Request $request, Candidature $candidature): Response
    {
       
            $entityManager = $this->getDoctrine()->getManager();
            $candidature->setAccepte(true) ; 
            $entityManager->flush();
            $this->addFlash('sucesss', "sucesss");

            return $this->redirectToRoute('candidature_show', ['id'=> $candidature->getId()]);
    }


  


}
