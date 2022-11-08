<?php

namespace App\Controller;

use Exception;
use App\Entity\Pays;
use App\Form\PaysType;
use App\Repository\PaysRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
/**
 * @Route("/pays")
 *  @IsGranted("ROLE_ADMIN")
 */
class PaysController extends AbstractController
{
    /**
     * @Route("/", name="pays_index", methods={"GET"})
     */
    public function index(PaysRepository $paysRepository): Response
    {
        return $this->render('pays/index.html.twig', [
            'pays' => $paysRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="pays_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $pay = new Pays();
        $form = $this->createForm(PaysType::class, $pay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pay);
            $entityManager->flush();

            return $this->redirectToRoute('pays_index');
        }

        return $this->render('pays/new.html.twig', [
            'pay' => $pay,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id_pays}", name="pays_show", methods={"GET"})
     */
    public function show(Pays $pay): Response
    {
        return $this->render('pays/show.html.twig', [
            'pay' => $pay,
        ]);
    }

    /**
     * @Route("/{id_pays}/edit", name="pays_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pays $pay): Response
    {
        $form = $this->createForm(PaysType::class, $pay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pays_index');
        }

        return $this->render('pays/edit.html.twig', [
            'pay' => $pay,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id_pays}", name="pays_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pays $pay): Response
    {   try {

    
        if ($this->isCsrfTokenValid('delete'.$pay->getIdPays(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pay);
            $entityManager->flush();
        }}
        catch( Exception $e ) {
            return $this->redirectToRoute('pays_edit' , ['id_pays'=>$pay->getIdPays()]);
        }
        return $this->redirectToRoute('pays_index');
    }
}
