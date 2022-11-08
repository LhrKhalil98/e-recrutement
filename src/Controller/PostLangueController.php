<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Entity\PostLangue;
use App\Form\PostLangueType;
use App\Repository\PosteRepository;
use App\Repository\PostLangueRepository;
use Error;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @Route("/post/langue")
 *  @IsGranted("ROLE_ADMIN")
 */
class PostLangueController extends AbstractController
{
    /**
     * @Route("/", name="post_langue_index", methods={"GET"})
     */
    public function index(PosteRepository $postLangueRepository): Response
    {
        return $this->render('post_langue/index.html.twig', [
            'post_langues' => $postLangueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="post_langue_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $poste = new Poste();
        $form = $this->createForm(PostLangueType::class, $poste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $poste->setDateCreation(new \DateTime('now')); 
            $entityManager->persist($poste);
          
            $entityManager->flush();

            return $this->redirectToRoute('post_langue_index');
        }

        return $this->render('post_langue/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_langue_show", methods={"GET"})
     */
    public function show(PostLangue $postLangue): Response
    {
        return $this->render('post_langue/show.html.twig', [
            'post_langue' => $postLangue,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_langue_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Poste $postLangue): Response
    {
        $form = $this->createForm(PostLangueType::class, $postLangue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_langue_index');
        }

        return $this->render('post_langue/edit.html.twig', [
            'post_langue' => $postLangue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_langue_delete", methods={"GET"})
     */
    public function delete(Request $request, $id , PosteRepository $posteRepository): Response
    {
        try{
            $poste = $posteRepository->findOneBy(['id'=>$id]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($poste);
            $entityManager->flush();
        
    }

        catch( Exception  $e) {
            return $this->redirectToRoute('post_langue_edit' , ['id'=>$poste->getId()]);
        }

        return $this->redirectToRoute('post_langue_index');
    }
}
