<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Entity\Entretien;
use App\Form\EntretienObservation;
use App\Form\EntretienType;
use App\Repository\CandidatureRepository;
use App\Repository\EntretienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/entretien")
 *  @IsGranted("ROLE_ADMIN")
 */
class EntretienController extends AbstractController
{
    /**
     * @Route("/", name="entretien_index", methods={"GET"})
     */
    public function index(EntretienRepository $entretienRepository): Response
    {
        return $this->render('entretien/index.html.twig', [
            'entretiens' => $entretienRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="entretien_new", methods={"GET","POST"})
     */
    public function new(Request $request , Candidature $candidature , EntretienRepository $entretien_rep): Response
    {
        $entretiens = $entretien_rep->findBy(['candidat'=>$candidature]) ; 
        foreach($entretiens as $entretien) {
            if($entretien->getEtat()== false ) {
                $this->addFlash('warning', " Au plus un  entretien encours ");
                return $this->redirectToRoute('entretien_candidat_show', ['id'=> $candidature->getId()]);
            }
        }

        $entretien = new Entretien();
        $form = $this->createForm(EntretienType::class, $entretien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entretien->setCandidat($candidature);
            $entretien->setEtat(false);
            $entityManager->persist($entretien);
            $entityManager->flush();

            return $this->redirectToRoute('entretien_candidat_show', ['id'=> $candidature->getId()]);
         }

        return $this->render('entretien/new.html.twig', [
            'entretien' => $entretien,
            'candidat'=>$candidature , 
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entretien_show", methods={"GET", "POST"})
     */
    public function show(Request $request , Entretien $entretien ): Response
    {
        
        $form = $this->createForm(EntretienObservation::class, $entretien);
        $form->handleRequest($request);
        $candidature = $entretien->getCandidat(); 

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('entretien_show', ['id'=> $entretien->getId()]);
        }
        return $this->render('entretien/show.html.twig', [
            'entretien' => $entretien,
            'form' => $form->createView(),
            'candidat'=>$candidature

        ]);
    }

    /**
     * @Route("/{id}/edit", name="entretien_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Entretien $entretien): Response
    {
        $form = $this->createForm(EntretienType::class, $entretien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('entretien_show', ['id'=> $entretien->getId()]);
        }

        return $this->render('entretien/edit.html.twig', [
            'entretien' => $entretien,
            'form' => $form->createView(),
            'candidat'=>$entretien->getCandidat()
        ]);
    }

    /**
     * @Route("/{id}", name="entretien_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Entretien $entretien): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entretien->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $candidature = $entretien->getCandidat();

            $entityManager->remove($entretien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('entretien_candidat_show', ['id'=> $candidature->getId()]);
    }

      /**
     * @Route("/{id}/ok", name="entretien_ok", methods={"GET"})
     */
    public function entretienok(Request $request, Entretien $entretien): Response
    {
       
            $entityManager = $this->getDoctrine()->getManager();
            $entretien->setEtat(true) ; 
            $candidature = $entretien->getCandidat();
            $entityManager->flush();
        

            return $this->redirectToRoute('entretien_candidat_show', ['id'=> $candidature->getId()]);
    }
}
