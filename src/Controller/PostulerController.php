<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Entity\Offre;
use App\Entity\Poste;
use App\Entity\Candidat;
use App\Form\CandidatType;

use App\Entity\Candidature;
use App\Entity\CandidaturePjs;
use App\Form\PaysCandidatType;
use App\Repository\PaysRepository;
use App\Repository\OffreRepository;
use App\Repository\PosteRepository;
use App\Repository\RegionRepository;
use App\Repository\OffrePjsRepository;
use App\Repository\PostLangueRepository;
use App\Repository\CandidatureRepository;
use App\Repository\OffreLangueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha;


class PostulerController extends AbstractController
{
    /**
     * @Route("/postuler/{id}", name="offre_afficher", methods={"GET"})
     */
    public function afficher(OffreRepository $offre_rep ,$id  ,OffreLangueRepository $offre_lang_rep , PostLangueRepository $poste_lang_rep , PosteRepository $post_rep ): Response
    {
        $offre = new Offre() ;
        $offre = $offre_rep->findOneBy(['url'=>$id]) ; 
        $posts = $offre->getPosts()->getValues();
        $offre_lang = $offre_lang_rep -> findOneBy(['ref_offre'=>$offre->getId()]);
        $post_lang = $poste_lang_rep ->findBy(['id_post'=>$posts ,'id_langue'=>'Francais']);
        $offre_lang = $offre_lang_rep -> findOneBy(['ref_offre'=>$offre->getId()]);
        
        return $this->render('offre/afficher.html.twig', [
            'offre' => $offre,
            'offre_lang'=>$offre_lang ,
            'post_lang'=>$post_lang , 
            'posts'=>$posts 
        ]);
    }
      /**
      * @Route("/postuler/pays/{id}", name="candidat_pay", methods={"GET","POST"} )
      */
      public function selectpays(Request $request ,$id ,PaysRepository $pays_rep ,OffrePjsRepository $offrePjsRepository, OffreRepository $offre_rep , CandidatureRepository $candidatRepository): Response
      {
        $candidat = ['message' => 'Offre Form'] ;   
        $form = $this->createForm(PaysCandidatType::class, $candidat);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $pay = $form->get('pays')->getData() ;
            $regioncandidat = $pay->getRegion() ; 
            $offre = $offre_rep -> findOneBy(['url'=> $id] ) ;
            $offrepjs = $offrePjsRepository ->findBy(['ref_offre'=>$offre]) ;
            foreach($offrepjs as $offrepj ){
                $region = $offrepj->getRegion() ;
                if($region == $regioncandidat ){
                    $session = $request->getSession();
                    $pay_session = $session->get('pay') ; 
                    $offre_session = $session->get('offre') ; 
                    $pay_session = $pay ;
                    $offre_session = $offre ;
                    $session->set('pay', $pay_session ) ;
                    $session->set('offre', $offre_session ) ;
                    return $this->redirectToRoute('candidat_new' , ['id'=> $id]);
                }
            }
            $this->addFlash('warning', "cette offre n'est pas inclus dans votre pays");



        }
        return $this->render('candidat/paysnew.html.twig', [
            'form' => $form->createView(),

        ]);
      }
      
     /**
      * @Route("/postuler/info/{id}", name="candidat_new", methods={"GET","POST"} )
      */
     public function new(Request $request  ,PaysRepository $pay_rep ,  OffreRepository $offre_rep , CandidatureRepository $candidatRepository): Response
     {
         $session = $request->getSession();
         $pay = $session->get('pay') ; 
         $offre = $session->get('offre') ; 
         $nbcandidat = $offre-> getNbCandidats() ; 

         $id = $offre->getId();
         $offre = $offre_rep -> findOneBy(['id'=> $id] ) ;
         $tokenProvider = $this->container->get('security.csrf.token_manager');
         $token = $tokenProvider->getToken('example')->getValue();
         $candidat = ['message' => 'Offre Form'] ;   
         $form = $this->createForm(CandidatType::class, $candidat);
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
            'attr' => ['class' => 'form-control'  ]
        ] ) ; 
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
            $datetime1 = new \DateTime(); 
            $birth = $form->get('cand_date_naissance')->getData() ;
            $age = $datetime1->diff( $birth, true)->y  ;
            if($age < 23 ){
                $this->addFlash('warning', "date naissance   non valid");

                return $this->render('candidat/new.html.twig', [
                    'candidat' => $candidat,
                    'form' => $form->createView(),
                ]);
             }
             $id2 =   $pay->getIdPays() ; 
             $pay =  $pay_rep->findOneBy(['id_pays'=>$id2]);
             $entityManager = $this->getDoctrine()->getManager();
             $candidature = new Candidature(); 
             $candidat = new Candidat() ; 
             $candidat->setPays($pay);
             $candidat->setCandNom($form->get('cand_nom')->getData());
             $candidat->setCandPrenom($form->get('cand_prenom')->getData());
             $candidat->setCandEmail($form->get('cand_email')->getData());
             $candidat->setSexe($form->get('sexe')->getData());
             $candidat->setCandTel($form->get('cand_tel')->getData());
             $candidat->setCandTelWhatsapp($form->get('cand_tel_whatsapp')->getData());
             $candidat->setCandDateNaissance($form->get('cand_date_naissance')->getData());
             $candidat->setCandAdresse($form->get('cand_adresse')->getData());
             $candidat->setVille($form->get('ville')->getData());
             $candidat->setEtatCivil($form->get('etat_civil')->getData());
             $candidat->setNbEnfant($form->get('nb_enfant')->getData());
             $candidature->setAccepte(false);
             $candidat->setCandCodePostal($form->get('cand_code_postal')->getData());
             $candidature->setCandId($candidat);  
             $candidature->setPoste($form->get('poste')->getData()) ;
             $id = $offre->getId();
             $offre = $offre_rep -> findOneBy(['id'=> $id] ) ;
             $candidature->setRefOffre($offre);
             $nbcandidat = $nbcandidat + 1 ; 
             $offre->setNbCandidats($nbcandidat) ; 
             $candidature->setEtat('phase1');
             $count = (int) $candidatRepository->countCand(Date('Y'))+1 ; 
             $candidature->setREF('REF/CAN/'.Date('Y').'/'.$count); 
             $entityManager->persist($candidature);
             $entityManager->persist($candidat);
             $entityManager->persist($offre);
             $session->clear();
             $entityManager->flush();
             $session = $request->getSession();
             $candidature_session = $session->get('candidature' ) ; 
             $candidature_session = $candidature ;
             $session->set('candidature', $candidature_session ) ;
             
             return $this->redirectToRoute('candidature_new' , ['id'=>  $offre->getUrl()]);
         }

         return $this->render('candidat/new.html.twig', [
             'candidat' => $candidat,
             'form' => $form->createView(),
             'token'=>$token
         ]);
     }
      /**
     * @Route("/postuler/doc/{id}", name="candidature_new", methods={"GET","POST"})
     */
    public function newCandidature(Request $request ,CandidatureRepository $candidature_rep ,  PaysRepository $paysRepository, RegionRepository $region_rep , OffrePjsRepository $offrePjsRepository ): Response
    {
        $session = $request->getSession();
  

        $candidature = $session->get('candidature') ; 
        $id = $candidature->getId(); 
        $offre = $candidature->getRefOffre() ;
        $pays = $candidature->getCandId()->getPays() ; 
        $offrePjs = $offrePjsRepository->findBy(['region'=>$pays->getRegion() , 'ref_offre'=>$offre->getId()]) ;
        $form = $this->createFormBuilder( ) ; 
        
       
        foreach($offrePjs as $offrePj ){
            $form->add($offrePj->getIdPsj()->getIdPieceJointe() , FileType::class , [
                'required' =>$offrePj->getIsRequired(),
                'label' =>$offrePj->getIdPsj()->getPieceJointe(),
                'attr' => ['class' => 'custom-file mb-3'] 
            ]) ; 
        }
        $form->add('captchaCode', CaptchaType::class, [
            'captchaConfig' => 'ExampleCaptchaUserRegistration',
            'constraints' => [  new ValidCaptcha([  'message' => 'Invalid captcha, please try again']),] , 
            ]);

        $form = $form ->getForm(); 
        $form->handleRequest($request); 
        if($form->isSubmitted()&& $form->isValid() )
        {
         foreach($offrePjs as $offrePj)
         {
            if( $form->get($offrePj->getIdPsj()->getIdPieceJointe())->getData() != null )
            {

                if($form->get($offrePj->getIdPsj()->getIdPieceJointe())->getData()->guessExtension() == 'pdf' ||$form->get($offrePj->getIdPsj()->getIdPieceJointe())->getData()->guessExtension() == 'docx'  ){
                    $size = filesize($form->get($offrePj->getIdPsj()->getIdPieceJointe())->getData());
                    if ($size >= 10000000 ){
                        $this->addFlash('warning', "La taille de fichier est trop élevée ");
                        return $this->redirectToRoute('candidature_new', ['id'=>  $offre->getUrl()]);
                    }

                    $entityManager = $this->getDoctrine()->getManager();
                    $candidaturepjs = new CandidaturePjs() ; 
                    $file =$form->get($offrePj->getIdPsj()->getIdPieceJointe())->getData(); 
                    $nomFile = md5(uniqid()).'.'.$file->guessExtension() ;
                    $file->move($this->getParameter('upload_directory'),$nomFile) ; 
                    $candidaturepjs->setCandidatureId($candidature_rep->findOneBy(['id'=>$id])) ;
                    $candidaturepjs->setIdPieceJointe($offrePj->getIdPsj()); 
                    $candidaturepjs->setDocumment($nomFile);
                    $entityManager->persist($candidaturepjs);
               }
              else {
                 $this->addFlash('warning', " PDF or  WORD ");
                  return $this->redirectToRoute('candidature_new', ['id'=>  $offre->getUrl()]);
                   }
            }
     
         }
         $entityManager->flush() ;

         return $this->redirectToRoute('candidat_index');

        }
     

        return $this->render('candidat/piecesjointe.html.twig', [
            'form'=>$form->createView() , 

        ]);
    }
    
   

    /**
     * @Route("/success ", name="candidat_index", methods={"GET"})
     */
    public function index(  ): Response
    {
        return $this->render('success.html.twig', [
        ]);
    }




    
}
