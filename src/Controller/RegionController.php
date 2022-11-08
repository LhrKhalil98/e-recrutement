<?php

namespace App\Controller;

use App\Entity\Region;
use App\Form\RegionType;
use App\Repository\PaysRepository;
use App\Repository\RegionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @Route("/region")
 *  @IsGranted("ROLE_ADMIN")
 */
class RegionController extends AbstractController
{
    /**
     * @Route("/", name="region_index", methods={"GET"})
     */
    public function index(RegionRepository $regionRepository): Response
    {
        return $this->render('region/index.html.twig', [
            'regions' => $regionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="region_new", methods={"GET","POST"})
     */
    public function new(Request $request , PaysRepository $pays_rep): Response
    {
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
         
            foreach( $region->getPays() as $pays){
                $pay = $pays_rep->find($pays) ; 
                $pay->setRegion($region); 
            }
            $entityManager->persist($region);
            $entityManager->flush();

            return $this->redirectToRoute('region_index');
        }

        return $this->render('region/new.html.twig', [
            'region' => $region,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id_region}", name="region_show", methods={"GET"})
     */
    public function show(Region $region): Response
    {
        return $this->render('region/show.html.twig', [
            'region' => $region,
        ]);
    }

    /**
     * @Route("/{id_region}/edit", name="region_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Region $region ,PaysRepository $pays_rep): Response
    {
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach( $region->getPays() as $pays){
                $pay = $pays_rep->find($pays) ; 
                $pay->setRegion($region); 
            }
            $this->getDoctrine()->getManager()->flush();
            

            return $this->redirectToRoute('region_index');
        }

        return $this->render('region/edit.html.twig', [
            'region' => $region,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id_region}", name="region_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Region $region): Response
    {
        if ($this->isCsrfTokenValid('delete'.$region->getIdRegion(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($region);
            $entityManager->flush();
        }

        return $this->redirectToRoute('region_index');
    }
    
      /**
     * @Route("/{id}/{id2}/supprimer", name="sup_pays",methods={"POST"})
     */
    public function desarchiver( $id2  , Request $request,Region $region , PaysRepository $pays_rep)
    {
        if ($this->isCsrfTokenValid('sup'.$region->getIdRegion(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $region->removePay($pays_rep->findOneBy(['id_pays'=>$id2]));
            $entityManager->flush();
        }
        return $this->redirect($this->generateUrl('region_show' , [ 'id_region' =>$region ]));

    }
       
}
