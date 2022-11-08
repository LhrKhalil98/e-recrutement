<?php

namespace App\Controller;

use App\Form\LienType;
use App\Entity\LienEntretien;
use Symfony\Component\Mime\Email;
use App\Repository\CandidatureRepository;
use App\Repository\LienEntretienRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class ListeController extends AbstractController
{
    /**
     * @Route("/liste", name="liste")
     *  @IsGranted("ROLE_ADMIN")
     */
    public function index(): Response
    {
        return $this->render('liste/index.html.twig', [
            'controller_name' => 'ListeController',
        ]);
    }

      /**
     * @Route("/liste/ajout/{id}", name="app_ajouter")
     */
    public function ajouterPanier($id , SessionInterface $session)
    {
        $panier = $session->get('panier' , []) ; 
        if(empty($panier[$id]))
            $panier[$id]=1; 
        else
            $panier[$id]++; 
        $session ->set('panier' , $panier) ;

        return $this->redirectToRoute('app_liste');
    }

     /**
     * @Route("/liste/supp/{id}", name="app_supprimer")
     */
    public function supprimerPanier($id , SessionInterface $session)
    {
        $panier = $session->get('panier' , []) ; 
        if(!empty($panier[$id]))
            unset($panier[$id]) ; 
     
        $session ->set('panier' , $panier) ; 

        return $this->redirectToRoute('app_liste');

    }
     /**
     * @Route("/liste/afficher", name="app_liste" , methods={"GET", "POST"})
     */
    public function afficherPanier( Request $request ,  LienEntretienRepository $lienEntretienRepository , \Swift_Mailer $mailer ,  CandidatureRepository $candidat_repo  ,  SessionInterface $session )
    { 
        $panier = $session->get('panier', []); 

        $lien = new LienEntretien () ;

        $form = $this->createForm(LienType::class, $lien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach($panier as $id => $quantite){
                $candidat = $candidat_repo->find($id) ;
                $lien->addCandidature($candidat);
                $lien->setDate( new \DateTime('now'));
                $mail = new PHPMailer(true);
                
                try {
                    //Server settings
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                    $mail->isSMTP();      
                    $mail->Host       = 'localhost';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = false;                                   //Enable SMTP authentication
                    $mail->SMTPAutoTLS = false; 
                    $mail->SMTPSecure = false; 
                    $mail->Username   = 'tunisie@weconseil.com';                     //SMTP username
                    $mail->Password   = 'Hamida20222';                               //SMTP password
                    $mail->Port       = 25;    
                 
                          //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
                                                   
                    //Recipients
                    $mail->setFrom('tunisie@weconseil.com', 'WeConseil');
                    $mail->addAddress($candidat->getCandId()->getCandEmail(), 'Candidature');     //Add a recipient
                  
            
                   
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Lien Entretien';
                    $mail->Body    = 'Madame/Mademoiselle/Monsieur, Votre candidature  a retenu toute notre attention et nous souhaiterions vous rencontrer et voici le lien de meet '.$form->get('lien')->getData() ;
        
                    $mail->send();
                    echo 'Message has been sent';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }

            $this->getDoctrine()->getManager()->persist($lien) ; 
           
            $this->getDoctrine()->getManager()->flush();
            $session->remove('panier'); 

            return $this->redirectToRoute('app_liste' );
        }
        $listes = [] ; 
        foreach($panier as $id => $quantite){
            $listes[]= [
                'candidat'=> $candidat_repo->find($id), 
            ];
        }
        return $this->render('liste/index.html.twig', [
            'controller_name' => 'PanierController',
            'listes'=> $listes ,
            'form' => $form->createView(),
            'liens'=>$lienEntretienRepository->findAll()

        ]);
    }


         /**
     * @Route("/liste/liens", name="app_liens")
     */
    public function listes( LienEntretienRepository $lienEntretienRepository)
    {


        return $this->render('liste/liens.html.twig', [
           
            'liens'=>$lienEntretienRepository->findAll()

        ]);

    }
}
