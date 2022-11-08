<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Entity\Offre;
use App\Entity\PieceJointe;
use App\Form\PieceJointeType;
use App\Repository\CandidaturePjsRepository;
use App\Repository\CandidatureRepository;
use App\Repository\OffreRepository;
use App\Repository\PieceJointeRepository;
use App\Repository\PosteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @Route("/piece/jointe")
 *  @IsGranted("ROLE_ADMIN")
 */
class PieceJointeController extends AbstractController
{
    /**
     * @Route("/", name="piece_jointe_index", methods={"GET"})
     */
    public function index(PieceJointeRepository $pieceJointeRepository): Response
    {
        return $this->render('piece_jointe/index.html.twig', [
            'piece_jointes' => $pieceJointeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/offre", name="pjs_offre", methods={"GET"})
     */
    public function offre_pjs( OffreRepository $offreRepository ): Response
    {
        return $this->render('piece_jointe/dossier_offre.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }

      /**
     * @Route("/offre/{id}/{id2}/candidats", name="pjs_offre_candidature", methods={"GET"})
     */
    public function candidats_offre_pjs(  Offre $offre ,  PosteRepository $posteRepository , $id2 ,CandidatureRepository $candidatureRepository ): Response
    {

        $poste = $posteRepository->findOneBy(['id'=>$id2]);
        return $this->render('piece_jointe/dossier_offre_candidats.html.twig', [
            'candidatures' => $candidatureRepository->findBy(['poste'=>$id2 , 'ref_offre'=>$offre]),
            'offre'=>$offre, 
            'poste'=>$poste
        ]);
    }

    
      /**
     * @Route("/offre/{id}/poste/", name="pjs_offre_poste", methods={"GET"})
     */
    public function candidats_offre_post( $id ,  OffreRepository $offreRepository ): Response
    {
        
        return $this->render('piece_jointe/dossier_postes.html.twig', [
            'offres' => $offreRepository->findOneBy(['id'=>$id ]),
            

        ]);
    }

     /**
     * @Route("/offre/candidats/{id}/documments", name="pjs_offre_candidature_documments", methods={"GET"})
     */
    public function documments_candidats_offre_pjs( $id , CandidaturePjsRepository $candidaturePjsRepository ): Response
    {
        return $this->render('piece_jointe/dossier_offre_candidats_documments.html.twig', [
            'documments' => $candidaturePjsRepository->findBy(['candidature_id'=>$id]),
        ]);
    }

    /**
     * @Route("/new", name="piece_jointe_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $pieceJointe = new PieceJointe();
        $form = $this->createForm(PieceJointeType::class, $pieceJointe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pieceJointe);
            $entityManager->flush();

            return $this->redirectToRoute('piece_jointe_index');
        }

        return $this->render('piece_jointe/new.html.twig', [
            'piece_jointe' => $pieceJointe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id_piece_jointe}", name="piece_jointe_show", methods={"GET"})
     */
    public function show(PieceJointe $pieceJointe): Response
    {
        return $this->render('piece_jointe/show.html.twig', [
            'piece_jointe' => $pieceJointe,
        ]);
    }

    /**
     * @Route("/{id_piece_jointe}/edit", name="piece_jointe_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PieceJointe $pieceJointe): Response
    {
        $form = $this->createForm(PieceJointeType::class, $pieceJointe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('piece_jointe_index');
        }

        return $this->render('piece_jointe/edit.html.twig', [
            'piece_jointe' => $pieceJointe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id_piece_jointe}", name="piece_jointe_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PieceJointe $pieceJointe): Response
    {   try {
        if ($this->isCsrfTokenValid('delete'.$pieceJointe->getIdPieceJointe(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pieceJointe);
            $entityManager->flush();
        }
       }
        catch( Exception  $e) {
            return $this->redirectToRoute('piece_jointe_edit' , ['id_piece_jointe'=>$pieceJointe->getIdPieceJointe()]);
        }
        return $this->redirectToRoute('piece_jointe_index');
    }
}
