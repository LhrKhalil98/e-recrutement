<?php

namespace App\Controller;

use App\Entity\Langue;
use App\Form\LangueType;
use App\Repository\LangueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/langue")
 *  @IsGranted("ROLE_ADMIN")
 */
class LangueController extends AbstractController
{
    /**
     * @Route("/", name="langue_index", methods={"GET"})
     */
    public function index(LangueRepository $langueRepository): Response
    {
        return $this->render('langue/index.html.twig', [
            'langues' => $langueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="langue_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $langue = new Langue();
        $form = $this->createForm(LangueType::class, $langue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($langue);
            $entityManager->flush();

            return $this->redirectToRoute('langue_index');
        }

        return $this->render('langue/new.html.twig', [
            'langue' => $langue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="langue_show", methods={"GET"})
     */
    public function show(Langue $langue): Response
    {
        return $this->render('langue/show.html.twig', [
            'langue' => $langue,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="langue_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Langue $langue): Response
    {
        $form = $this->createForm(LangueType::class, $langue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('langue_index');
        }

        return $this->render('langue/edit.html.twig', [
            'langue' => $langue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="langue_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Langue $langue): Response
    {
        if ($this->isCsrfTokenValid('delete'.$langue->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($langue);
            $entityManager->flush();
        }

        return $this->redirectToRoute('langue_index');
    }
}
