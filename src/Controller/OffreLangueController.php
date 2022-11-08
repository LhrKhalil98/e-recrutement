<?php

namespace App\Controller;

use App\Entity\OffreLangue;
use App\Form\OffreLangueType;
use App\Repository\OffreLangueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @Route("/offre/langue")
 *  @IsGranted("ROLE_ADMIN")
 */
class OffreLangueController extends AbstractController
{
    /**
     * @Route("/", name="offre_langue_index", methods={"GET"})
     */
    public function index(OffreLangueRepository $offreLangueRepository): Response
    {
        return $this->render('offre_langue/index.html.twig', [
            'offre_langues' => $offreLangueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="offre_langue_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $offreLangue = new OffreLangue();
        $form = $this->createForm(OffreLangueType::class, $offreLangue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offreLangue);
            $entityManager->flush();

            return $this->redirectToRoute('offre_langue_index');
        }

        return $this->render('offre_langue/new.html.twig', [
            'offre_langue' => $offreLangue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="offre_langue_show", methods={"GET"})
     */
    public function show(OffreLangue $offreLangue): Response
    {
        return $this->render('offre_langue/show.html.twig', [
            'offre_langue' => $offreLangue,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="offre_langue_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OffreLangue $offreLangue): Response
    {
        $form = $this->createForm(OffreLangueType::class, $offreLangue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('offre_langue_index');
        }

        return $this->render('offre_langue/edit.html.twig', [
            'offre_langue' => $offreLangue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="offre_langue_delete", methods={"DELETE"})
     */
    public function delete(Request $request, OffreLangue $offreLangue): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offreLangue->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offreLangue);
            $entityManager->flush();
        }

        return $this->redirectToRoute('offre_langue_index');
    }
}
