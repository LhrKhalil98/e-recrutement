<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Poste;
use App\Entity\Region;
use App\Form\OffreType;
use App\Entity\OffrePjs;
use App\Form\MissionType;
use App\Entity\PostLangue;
use App\Entity\OffreLangue;
use App\Entity\PieceJointe;
use App\Form\DocummentType;
use App\Form\PostOffreType;
use App\Data\SearchOffreData;
use App\Form\SearchOfferType;
use App\Form\SimpleOffreType;
use App\Entity\CategorieOffre;
use App\Repository\OffreRepository;
use App\Repository\PosteRepository;
use App\Repository\RegionRepository;
use App\Repository\OffrePjsRepository;
use App\Repository\PostLangueRepository;
use App\Repository\OffreLangueRepository;
use App\Repository\PieceJointeRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use ParagonIE\ConstantTime\Base64UrlSafe;
use Base64Url\Base64Url;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @Route("/offre")
 *  @IsGranted("ROLE_ADMIN")
 */
class OffreController extends AbstractController
{
    /**
     * @Route("/{id?}", name="offre_index", methods={"GET","POST"})
     */
    public function index(OffreRepository $offreRepository , $id,  Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

           
        $data = new SearchOffreData();
        $formFilter = $this->createForm(SearchOfferType::class , $data) ; 
        $formFilter->handleRequest($request) ;
        $date = new \DateTime('now') ; 
        $offres = $offreRepository->filterOffer($data) ; 
        foreach ($offres as $offre){
            if($offre->getDateFin() < $date){
                $offre->setEtat('Cloturer');
                $entityManager->flush() ;
            }
        }
        $doc =  ['message' => 'Offre Form'];
        $form = $this->createForm(DocummentType::class, $doc);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pjs =$form->get('pience_jointe')->getData();
            $offre = $offreRepository->findOneBy(['id'=>$id]);
            $offre_pjs = new OffrePjs ; 
            $offre_pjs ->setRefOffre($offre) ; 
            $offre_pjs ->setIdPsj($pjs) ; 
             $offre_pjs->setIsrequired($form->get('Isrequired')->getData()) ;

            $offre_pjs->setRegion($form->get('region')->getData()) ; 
            $entityManager->persist($offre_pjs) ; 
            $entityManager->flush() ;
            
            return $this->redirect($this->generateUrl('offre_index' ));
        }
        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
            'form'=>$form->createView() ,
            'formFilter' =>$formFilter->createView()
        ]);
    }

       /**
     * @Route("/archive/archive", name="offre_archive", methods={"GET"})
     */
    public function archives(OffreRepository $offreRepository): Response
    {

        return $this->render('offre/archive.html.twig', [
            'offres' => $offreRepository->findBy(['etat'=>'archive']),
        ]);
    }

    
  


     /**
     * @Route("/new/new", name="offre_new", methods={"GET","POST"})
     */
    public function newOffre(Request $request , OffreRepository $offre_rep ,  RegionRepository $regionRepository , PosteRepository $post_rep): Response
    {
        $offre =  ['message' => 'Offre Form'];
       
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
   
        if ($form->get('date_fin')->getData()  < $form->get('date_debut')->getData()  ) {
            return $this->redirectToRoute('offre_new');
        }
            $entityManager = $this->getDoctrine()->getManager();
            $offre = new Offre() ; 
            $offre_lang = new OffreLangue() ; 
            $offre->setDateCreation(  new \DateTime('now'));
            $offre->setEtat('en cours'); 
            $offre->setLieu($form->get('lieu')->getData());
            $offre->setIntituleOffre($form->get('intitule_offre')->getData());
            $offre->setIdCategorie($form->get('id_categorie')->getData());
            $offre->setSociete($form->get('societe')->getData()) ;
            $posts = $form->get('posts')->getData()  ; 
            foreach ($posts as $post ) {
                 $offre->addPost($post) ;
            }
            $cat = $form->get('id_categorie')->getData() ;
            $offre_lang -> setRefOffre($offre) ; 
            $offre_lang ->setDescription($form->get('description')->getData());
            $entityManager->persist($offre_lang);
            $offre->setDateDebut($form->get('date_debut')->getData()) ; 
            $offre->setDateFin($form->get('date_fin')->getData()) ; 
            $offre->setStatus(false); 
            $offre->setNbCandidats(0) ; 
            $count = (int) $offre_rep->countOffre(Date('Y') , $cat  )+1 ; 
         
            $offre->setREF('REF/'.$offre->getIdCategorie()->getAbreviation().'/'.Date('Y').'/'.$count); 
            $offre->setUrl(Base64Url::encode(random_bytes(9)));

            $entityManager->persist($offre);
            $entityManager->flush();
            return $this->redirectToRoute('offre_index');
        }
        return $this->render('offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="offre_show", methods={"GET"  ,"POST"})
     */
    public function show( Request $request , RegionRepository $region_rep, OffrePjsRepository $offre_pjs_rep ,  Offre $offre,  OffreRepository $offre_rep ,$id  ,OffreLangueRepository $offre_lang_rep , PostLangueRepository $poste_lang_rep , PosteRepository $post_rep ): Response
    {
       
       
        $region = $region_rep->findAll() ; 
        $posts = $offre->getPosts()->getValues();
        $offre_lang = $offre_lang_rep -> findOneBy(['ref_offre'=>$id]);
        $post_lang = $poste_lang_rep ->findBy(['id_post'=>$posts ,'id_langue'=>'Francais']);
        $offre_lang = $offre_lang_rep -> findOneBy(['ref_offre'=>$id]);
        $offre_pjs_data = $offre_pjs_rep->findBy(['ref_offre'=>$id]) ;
         /* ********************************* Ajouter Pjs  *************************************** */
        $doc =  ['message' => 'Offre Form'];
        $form = $this->createForm(DocummentType::class, $doc);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $pjs =$form->get('pience_jointe')->getData();
            $offre_pjs = new OffrePjs ; 
            $offre_pjs ->setRefOffre($offre) ; 
            $offre_pjs ->setIdPsj($pjs) ;
            $offre_pjs->setIsrequired($form->get('Isrequired')->getData()) ;
            $offre_pjs->setRegion($form->get('region')->getData()) ; 
            $entityManager->persist($offre_pjs) ; 
            $entityManager->flush() ;
            
            return $this->redirect($this->generateUrl('offre_show' , ['id'=> $id]));
        }
        /* ******************************************************************************* */

        /* *************************************** Edit Date **************************** */
       
        $formdate = $this->createForm(SimpleOffreType::class, $offre);
        $formdate->handleRequest($request);

        if ($formdate->isSubmitted() && $formdate->isValid()) {
            $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('offre_show' , ['id'=> $id]));
        }
        /* ******************************************************************************* */

        /* *************************************** Edit Mission Description **************************** */
        $offre_lang_data =  $offre_lang_rep->findOneBy(['ref_offre'=>$id]); 
        $formmission = $this->createForm(MissionType::class, $offre_lang_data);
        $formmission->handleRequest($request);

        if ($formmission->isSubmitted() && $formmission->isValid()) {
            $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('offre_show' , ['id'=> $id]));
        }
        /* ******************************************************************************* */

        /* *************************************** Edit POST **************************** */
        $formpost = $this->createFormBuilder( ) ; 

          $formpost
          ->add('posts', EntityType::class, [
            'class' => Poste::class ,
            'choice_label' => 'description',
            'data'=>$offre->getPosts() ,

            'multiple' => true ,
            'attr' => ['class' => 'form-control'  ]
        ]) ;
        $formpost = $formpost ->getForm(); 
        $formpost->handleRequest($request); 
  
          if ($formpost->isSubmitted() && $formpost->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();


            $posts = $formpost->get('posts')->getData()  ; 
            
            foreach ($posts as $post ) {
             
                 $offre->addPost($post) ;
             
            }
            $entityManager->flush(); 
             
         
          return $this->redirect($this->generateUrl('offre_show' , ['id'=> $id]));
          }
        /* ******************************************************************************* */

        
       
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
            'offre_lang'=>$offre_lang ,
            'post_lang'=>$post_lang , 
            'posts'=>$posts ,
            'form' => $form->createView(),
            'pjs'=> $offre_pjs_data,
            'regions'=>$region ,
            'formdate' => $formdate->createView(),
            'formmission'=> $formmission->createView(),
            'formpost'=> $formpost->createView()



        ]);
    }


    /**
     * @Route("/{id}/edit", name="offre_edit_date", methods={"GET","POST"})
     */
    public function editdate (Request $request, Offre $offre): Response
    {
        
        $formdate = $this->createForm(SimpleOffreType::class, $offre);
        $formdate->handleRequest($request);

        if ($formdate->isSubmitted() && $formdate->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('offre_index');
        }

        return $this->render('offre/formeditdate.html.twig', [
            'offre' => $offre,
            'form' => $formdate->createView(),
        ]);
    }

    /**
     * @Route("/documment/{id}", name="offre_documment", methods={"GET"})
     */
    public function docuumentsOffre(Request $request, Offre $offre , RegionRepository $region_rep ,OffrePjsRepository $offrePjsRepository): Response
    {
         
        $offrepjs = $offrePjsRepository->findBy(['ref_offre'=>$offre]) ; 
        $regions [] = new Region  ;
        foreach( $offrepjs as $offrepj) {
           if (array_search( $offrepj->getRegion(),$regions  )== false ){
               $region = $region_rep->findOneBy(['id_region'=>$offrepj->getRegion()->getIdRegion()]);
               $regions [] = $region ;        
           }           
        }     
        return $this->render('offre/docummentoffre.html.twig' ,[
            'regions'=> $regions,
            'pjs'=> $offrepjs ,
            'offre'=>$offre 
        ]);
    }

         /**
     * @Route("/show/{id}/modifier", name="offre_modifier")
     */
    public function modifieroffre(Request $request, OffreRepository $offre_rep,Offre $offre , OffreLangueRepository $offre_lang_rep): Response
    {

            $offre_lang =$offre_lang_rep ->findOneBy(['ref_offre'=>$offre ]);

            $form = $this->createFormBuilder( ) ; 
            

            $form
                ->add('intitule' , TextType::class , [
                'data'=>$offre->getIntituleOffre() ,
                'attr' => ['class' => 'form-control'] 
                 ]) 
                ->add('categorie' , EntityType::class , [
                'class'=>CategorieOffre::class , 
                'data'=>$offre->getIdCategorie() ,
                'choice_label' => 'cat_description',
                'placeholder' => 'Select Categorie',
                'disabled'=>$offre->getStatus() ,

                'label' => 'Categorie' ,
                'attr' => ['class' => 'form-control'  ]
                 ]) 
                 ->add('posts', EntityType::class, [
                    'class' => Poste::class ,
                    'choice_label' => 'description',
                    'data'=>$offre->getPosts() ,
                    'disabled'=>$offre->getStatus() ,
                    'multiple' => true ,
                    'attr' => ['class' => 'form-control'  ]
                ])
           
                ->add('description' , CKEditorType::class , [
                'data'=>$offre_lang->getDescription() ,
                'attr' => ['class' => 'form-control'] 
                 ]) 
             
                ->add('lieu' , TextType::class , [
                'data'=>$offre->getLieu() ,
                'attr' => ['class' => 'form-control'] 
                 ]) 
                 
                ->add('societe' , TextType::class , [
                'data'=>$offre->getSociete() ,
                'attr' => ['class' => 'form-control'] 
                 ]) 
                ->add('date_debut' , DateType::class , [
                'data'=>$offre->getDateDebut() ,
                'widget' => 'single_text',   
                'attr' => ['class' => 'form-control'  ]
                 ]) 
                ->add('date_fin' , DateType::class , [
                'data'=>$offre->getDateFin() ,
                'widget' => 'single_text',   
                'attr' => ['class' => 'form-control'  ]
                 ]) 
           
            ; 
        $form = $form ->getForm(); 
        $form->handleRequest($request); 
        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('date_fin')->getData()  < $form->get('date_debut')->getData()  ) {
                return $this->redirectToRoute('offre_dupliquer' , ['id'=>$offre]);
            }
             
            $entityManager = $this->getDoctrine()->getManager();
            
            $offre->setDateCreation(  new \DateTime('now'));
            $offre->setEtat('en cours'); 
            $offre->setLieu($form->get('lieu')->getData());
            $offre->setIntituleOffre($form->get('intitule')->getData());
            $offre->setIdCategorie($form->get('categorie')->getData());
            $posts = $form->get('posts')->getData()  ; 
            foreach ($posts as $post ) {
          
                 $offre->addPost($post) ;
            }
            $offre->setSociete($form->get('societe')->getData()) ;

            $offre_lang -> setRefOffre($offre) ; 
            $offre_lang ->setDescription($form->get('description')->getData());
            $entityManager->persist($offre_lang);
            $offre->setDateDebut($form->get('date_debut')->getData()) ; 
            $offre->setDateFin($form->get('date_fin')->getData()) ; 
            $offre->setStatus(false); 
         
            $entityManager->flush(); 
  
            return $this->redirect($this->generateUrl('offre_show' , ['id'=> $offre->getId()]));

        }

        return $this->render('offre/modifier.html.twig' ,[
            'form'=> $form->createView() ,
            'offre'=>$offre 
        ]);
    }


     /**
     * @Route("/show/{id}/dupliquer", name="offre_dupliquer")
     */
    public function dupliquer(Request $request, OffreRepository $offre_rep,Offre $offre , OffreLangueRepository $offre_lang_rep): Response
    {

            $offre_lang =$offre_lang_rep ->findOneBy(['ref_offre'=>$offre ]);

            $form = $this->createFormBuilder( ) ; 
       

            $form
                ->add('intitule' , TextType::class , [
                'data'=>$offre->getIntituleOffre() ,
                'attr' => ['class' => 'form-control'] 
                 ]) 
                ->add('categorie' , EntityType::class , [
                'class'=>CategorieOffre::class , 
                'data'=>$offre->getIdCategorie() ,
                'choice_label' => 'cat_description',
                'placeholder' => 'Select Categorie',

                'label' => 'Categorie' ,
                'attr' => ['class' => 'form-control'  ]
                 ]) 
                 ->add('posts', EntityType::class, [
                    'class' => Poste::class ,
                    'choice_label' => 'description',
                    'data'=>$offre->getPosts() ,

                    'multiple' => true ,
                    'attr' => ['class' => 'form-control'  ]
                ])
           
                ->add('description' , CKEditorType::class , [
                'data'=>$offre_lang->getDescription() ,
                'attr' => ['class' => 'form-control'] 
                 ]) 
           
                ->add('lieu' , TextType::class , [
                'data'=>$offre->getLieu() ,
                'attr' => ['class' => 'form-control'] 
                 ]) 
                ->add('societe' , TextType::class , [
                'data'=>$offre->getSociete() ,
                'attr' => ['class' => 'form-control'] 
                 ]) 
                ->add('date_debut' , DateType::class , [
                'data'=>$offre->getDateDebut() ,
                'widget' => 'single_text',   
                'attr' => ['class' => 'form-control'  ]
                 ]) 
                ->add('date_fin' , DateType::class , [
                'data'=>$offre->getDateFin() ,
                'widget' => 'single_text',   
                'attr' => ['class' => 'form-control'  ]
                 ]) 
           
            ; 
        $form = $form ->getForm(); 
        $form->handleRequest($request); 
        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('date_fin')->getData()  < $form->get('date_debut')->getData()  ) {
                return $this->redirectToRoute('offre_dupliquer' , ['id'=>$offre]);
            }
             
            $entityManager = $this->getDoctrine()->getManager();
            $offre = new Offre() ; 
            $offre_lang = new OffreLangue() ; 
            $offre->setDateCreation(  new \DateTime('now'));
            $offre->setEtat('en cours'); 
            $offre->setLieu($form->get('lieu')->getData());
            $offre->setIntituleOffre($form->get('intitule')->getData());
            $offre->setIdCategorie($form->get('categorie')->getData());
            $posts = $form->get('posts')->getData()  ; 
            foreach ($posts as $post ) {
          
                 $offre->addPost($post) ;
            }
  
            $offre_lang -> setRefOffre($offre) ; 
            $offre_lang ->setDescription($form->get('description')->getData());
            $entityManager->persist($offre_lang);
            $offre->setDateDebut($form->get('date_debut')->getData()) ; 
            $offre->setDateFin($form->get('date_fin')->getData()) ; 
            $offre->setStatus(false); 
            $offre->setNbCandidats(0) ; 

            $cat = $form->get('categorie')->getData() ;
            $offre->setSociete($form->get('societe')->getData()) ;


            $count = (int) $offre_rep->countOffre(Date('Y'),$cat)+1 ; 
           
   
            $offre->setREF('REF/'.$offre->getIdCategorie()->getAbreviation().'/'.Date('Y').'/'.$count); 
            $offre->setUrl(crypt($offre->getId() ,'rl')) ;

            $entityManager->persist($offre);
            $entityManager->flush(); 
  
            return $this->redirect($this->generateUrl('offre_show' , ['id'=> $offre->getId()]));

        }

        return $this->render('offre/dupliquer.html.twig' ,[
            'form'=> $form->createView() ,
            'offre'=>$offre 

        ]);
    }

    /**
     * @Route("/{id}/activer", name="offre_activer", methods={"GET"})
     */
    public function activer(Request $request, Offre $offre , OffrePjsRepository $offrePjsRepository): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            if ($offre->getStatus()==true){
                $offre->setStatus(false) ; 
            }
            else{
                $offrepjs = $offrePjsRepository->findBy(['ref_offre'=>$offre]) ; 
                if(!$offrepjs) {
                    $this->addFlash('warning', "Aucune region affectée ");
                    return $this->redirect($this->generateUrl('offre_show' , ['id'=> $offre->getId()]));

                } 
                $offre->setStatus(true) ; 

            }
            $entityManager->flush();
        

            return $this->redirect($this->generateUrl('offre_show' , ['id'=> $offre->getId()]));
        }
    
    /**
     * @Route("/{id}/archiver", name="offre_archiver")
     */
    public function archive(Request $request, Offre $offre): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $offre->setEtat('archive');
            $offre->setStatus(false);
            $entityManager->flush();
        

        return $this->redirectToRoute('offre_index');
    }

      /**
     * @Route("/{id}/desarchiver", name="offre_desarchiver")
     */
    public function desarchiver(Request $request, Offre $offre): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $offre->setEtat('en cours');
            $entityManager->flush();
      

            return $this->redirectToRoute('offre_index');
        }

      /**
     * @Route("/show/{id}/pjs", name="offre_pjs",methods={"GET","POST"})
     */
    public function AffectationPieceJointe(Request $request , Offre $offre , OffrePjsRepository $offrePjsRepository,  RegionRepository $regionRepository): Response
    {
        

        $form = $this->createFormBuilder();
     
        $form
            ->add('region', EntityType::class , [
            'label'=>'Region' ,
            'class'=> Region::class , 

            'choice_label' => 'region',
            'attr' => ['class' => 'form-control'] 
            ]) 
            ->add('obligatoire', EntityType::class , [
            'label'=>'Documents obligatoires' ,
            'class'=> PieceJointe::class , 
            'choice_label' => 'piece_jointe',
            'multiple'=>true , 
            'required'=> false ,
            'attr' => ['class' => 'form-control'] 
            ]) 
            ->add('optionel', EntityType::class , [
            'label'=>'Documents optionnels' ,
            'class'=> PieceJointe::class , 
            'choice_label' => 'piece_jointe',
            'multiple'=>true , 
            'required'=>false,
            'attr' => ['class' => 'form-control'] 
            ]) 
         ;    
       
        $form ->add('Save' , SubmitType::class , [ 
            'attr' => ['class' => 'btn btn-success'] 

        ]) ; 

         $form = $form ->getForm(); 
         $form->handleRequest($request); 
         if ($form->isSubmitted() && $form->isValid()) {

                $region = $form->get('region')->getData();
                $data= $offrePjsRepository->findBy(['ref_offre'=>$offre , 'region'=>$region ]) ;
                if($data){
                    $this->addFlash('warning', "Region deja existe ");

                    return $this->render('offre/ajouterpiecejointe.html.twig' ,[
                        'form'=> $form->createView() ,
                        'offre'=>$offre 

                    ]);
                }

                $pjs [] = new PieceJointe() ;

                $entityManager = $this->getDoctrine()->getManager();
                foreach ($form->get('obligatoire')->getData() as $pjob){
                $pjs[] =  $pjob ; 
                $offre_pjs = new OffrePjs ; 
                $offre_pjs ->setRefOffre($offre) ; 
                $offre_pjs ->setIdPsj($pjob) ;
                $offre_pjs->setIsrequired(true) ;
                $offre_pjs->setRegion($region) ; 
                $entityManager->persist($offre_pjs) ; 
                }
                foreach ($form->get('optionel')->getData() as $pjop){
                if (array_search( $pjop,$pjs  )== true ){
                    $this->addFlash('warning', "Duplicated ");

                    return $this->render('offre/ajouterpiecejointe.html.twig' ,[
                        'form'=> $form->createView() ,
                        'offre'=>$offre 

                    ]);
                }
                $offre_pjs = new OffrePjs ; 
                
                $offre_pjs ->setRefOffre($offre) ; 
                $offre_pjs ->setIdPsj($pjop) ;
                $offre_pjs->setIsrequired(false) ;
                $offre_pjs->setRegion($region) ; 
                $entityManager->persist($offre_pjs) ; 
                }
                $entityManager->flush() ;
           
                return $this->redirectToRoute('offre_documment', ['id'=> $offre->getId()]);


         }

         return $this->render('offre/ajouterpiecejointe.html.twig' ,[
            'form'=> $form->createView() ,
            'offre'=>$offre 

        ]);
    }
      /**
     * @Route("/show/{id}/pjs/{id2}", name="modifier_offre_pjs",methods={"GET","POST"})
     */
    public function modifierPieceJointe(Request $request ,Offre $offre , $id2 , PieceJointeRepository $pieceJointeRepository,OffrePjsRepository $offrePjsRepository,  RegionRepository $regionRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder();
        $region = $regionRepository->findOneBy(['id_region'=>$id2]);
        $dataOB = $offrePjsRepository->findBy(['ref_offre'=>$offre , 'region'=>$region , 'Isrequired'=>true]) ;
        $dataOP = $offrePjsRepository->findBy(['ref_offre'=>$offre , 'region'=>$region , 'Isrequired'=>false]) ;
        $pjob = [] ; 
        $pjop = [] ; 
        foreach ($dataOB as $data){
            $pjob []= $data->getIdPsj();     
        }
        foreach ($dataOP as $data){
            $pjop []= $data->getIdPsj();
        }
       

        $form
            ->add('region', EntityType::class , [
            'label'=>'Region' ,
            'class'=> Region::class , 
            'data'=>$region ,
            'choice_label' => 'region',
            'disabled'=>true,
            'attr' => ['class' => 'form-control'] 
            ]) 
            ->add('obligatoire', EntityType::class , [
            'label'=>'Documents obligatoires' ,
            'class'=> PieceJointe::class , 
            'choice_label' => 'piece_jointe',
            'data'=>$pjob ,

            'multiple'=>true , 
            'required'=> false ,
            'attr' => ['class' => 'form-control'] 
            ]) 
            ->add('optionel', EntityType::class , [
            'label'=>'Documents optionnels' ,
            'class'=> PieceJointe::class , 
            'choice_label' => 'piece_jointe',
            'data'=>$pjop ,
            'required'=> false , 

            'multiple'=>true , 
            'attr' => ['class' => 'form-control'] 
            ]) 
         ;    
       
        $form ->add('Save' , SubmitType::class , [ 
            'attr' => ['class' => 'btn btn-success'] 

        ]) ; 

         $form = $form ->getForm(); 
         $form->handleRequest($request); 
         if ($form->isSubmitted() && $form->isValid()) {
            foreach ($dataOB as $data){
                $entityManager->remove($data);  
            }
            foreach ($dataOP as $data){
                $entityManager->remove($data);
            }

                $pjs [] = new PieceJointe() ;


                $entityManager = $this->getDoctrine()->getManager();
                foreach ($form->get('obligatoire')->getData() as $pjob){
                
                $pjs[] =  $pjob ; 
                $offre_pjs = new OffrePjs ; 
                $offre_pjs ->setRefOffre($offre) ; 
                $offre_pjs ->setIdPsj($pjob) ;
                $offre_pjs->setIsrequired(true) ;
                $offre_pjs->setRegion($region) ; 
                $entityManager->persist($offre_pjs) ; 
                }
                foreach ($form->get('optionel')->getData() as $pjop){
                    if (array_search( $pjop,$pjs  )== true ){
                        $this->addFlash('warning', "Duplicated ");

                        return $this->render('offre/ajouterpiecejointe.html.twig' ,[
                            'form'=> $form->createView() ,
                            
                        ]);
                    }
                $offre_pjs = new OffrePjs ; 
                
                $offre_pjs ->setRefOffre($offre) ; 
                $offre_pjs ->setIdPsj($pjop) ;
                $offre_pjs->setIsrequired(false) ;
                $offre_pjs->setRegion($region) ; 
                $entityManager->persist($offre_pjs) ; 
                }
                $entityManager->flush() ;
           
        return $this->redirectToRoute('offre_documment', ['id'=> $offre->getId()]);


         }

         return $this->render('offre/modifierpiecejointe.html.twig' ,[
            'form'=> $form->createView() ,
            'offre'=>$offre ,
            'region'=>$region , 

        ]);
    }


    /**
     * @Route("/show/{id}/pjs/{id2}/delete", name="delete_region",methods={"GET"})
     */
    public function deleteRegion(Request $request, Offre $offre , $id2 ,OffrePjsRepository $offrePjsRepository,  RegionRepository $regionRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $region = $regionRepository->findOneBy(['id_region'=>$id2]);
        $data = $offrePjsRepository->findBy(['ref_offre'=>$offre , 'region'=>$region ]) ;
     
        foreach ($data as $data){
            $entityManager->remove($data);  
        }
        $entityManager->flush() ;

   
        return $this->redirectToRoute('offre_documment', ['id'=> $offre->getId()]);
    }


    
  
}
