<?php

namespace App\Controller;

use Exception;
use App\Entity\CategorieOffre;
use App\Form\CategorieOffreType;
use App\Repository\CategorieOffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @Route("/categorie/offre")
 *  @IsGranted("ROLE_ADMIN")
 */
class CategorieOffreController extends AbstractController
{
    /**
     * @Route("/", name="categorie_offre_index", methods={"GET"})
     */
    public function index(CategorieOffreRepository $categorieOffreRepository): Response
    {
        return $this->render('categorie_offre/index.html.twig', [
            'categorie_offres' => $categorieOffreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="categorie_offre_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $categorieOffre = new CategorieOffre();
        $form = $this->createForm(CategorieOffreType::class, $categorieOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorieOffre);
            $entityManager->flush();

            return $this->redirectToRoute('categorie_offre_index');
        }

        return $this->render('categorie_offre/new.html.twig', [
            'categorie_offre' => $categorieOffre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_offre_show", methods={"GET"})
     */
    public function show(CategorieOffre $categorieOffre): Response
    {
        return $this->render('categorie_offre/show.html.twig', [
            'categorie_offre' => $categorieOffre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categorie_offre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CategorieOffre $categorieOffre): Response
    {
        $form = $this->createForm(CategorieOffreType::class, $categorieOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categorie_offre_index');
        }

        return $this->render('categorie_offre/edit.html.twig', [
            'categorie_offre' => $categorieOffre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_offre_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CategorieOffre $categorieOffre): Response
    {
        try {
        if ($this->isCsrfTokenValid('delete'.$categorieOffre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categorieOffre);
            $entityManager->flush();
        }
       }
        catch( Exception $e ) {
            return $this->redirectToRoute('categorie_offre_edit' , ['id'=>$categorieOffre->getId()]);
        }
        return $this->redirectToRoute('categorie_offre_index');
    }
}
