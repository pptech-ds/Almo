<?php

namespace App\Controller\Admin;

use App\Entity\Information;
use App\Entity\InformationCategory;
use App\Form\Admin\InformationFormType;
use App\Form\InformationCategoryFormType;
use App\Repository\InformationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\InformationCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class InformationController extends AbstractController
{
    
    
    /**
     * @Route("/admin/information", name="admin_information_index")
     */
    public function informationIndex(InformationRepository $informationRepository, Request $request): Response
    {
        
        return $this->render('admin/information/index.html.twig', [
            'informations' => $informationRepository->findAll(),
        ]);
    }


    
    
    /**
     * @Route("/admin/information/add", name="admin_information_add")
     */
    public function informationAdd(Request $request): Response
    {
        $information = new Information();
        $form = $this->createForm(InformationFormType::class, $information);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $information->setUser($this->getUser());
            $information->setIsActive(True);
            $em = $this->getDoctrine()->getManager();
            $em->persist($information);
            $em->flush();

            $this->addFlash('success', 'Votre information a été ajouté avec succes !');
            
            return $this->redirectToRoute('admin_information_index',);
        }

        return $this->render('admin/information/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    



    /**
     * @Route("/admin/information/update/{id}", name="admin_information_update", requirements={"id"="\d+"})
     */
    public function informationUpdate(Information $information, Request $request): Response
    {
        // dd($information->getInformationCategory()->getName());

        $form = $this->createForm(InformationFormType::class, $information);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($information);
            $em->flush();

            $this->addFlash('success', 'Votre information a été modifié avec succes !');

            return $this->redirectToRoute('admin_information_index');
        }

        return $this->render('admin/information/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/information/activate/{id}", name="admin_information_activate", requirements={"id"="\d+"})
     */
    public function informationActivate(Information $information): Response
    {
        // dd($information);
        $information->setActive( ($information->getActive()) ?  false : true );

        $em = $this->getDoctrine()->getManager();
        $em->persist($information);
        $em->flush();

        return new Response('true');
    }



    /**
     * @Route("/admin/information/delete/{id}", name="admin_information_delete", requirements={"id"="\d+"})
     */
    public function informationDelete(Information $information): Response
    {
        // dd($information);
        // $information->setActive( ($information->getActive()) ?  false : true );

        $em = $this->getDoctrine()->getManager();
        $em->remove($information);
        $em->flush();

        $this->addFlash('success', 'Votre information a été supprimé avec succes !');

        return $this->redirectToRoute('admin_home');
    }
}
