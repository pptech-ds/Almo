<?php

namespace App\Controller\Admin;

use App\Entity\Ressource;
use App\Form\RessourceFormType;
use App\Entity\RessourceCategory;
use App\Form\RessourceCategoryFormType;
use App\Repository\RessourceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RessourceCategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class RessourceController extends AbstractController
{
    
    
    /**
     * @Route("/admin/ressource/category/{slug}", name="admin_ressource_by_category_index")
     */
    public function ressourceByCategoryIndex(RessourceRepository $ressourceRepository, Request $request): Response
    {
        
        $category = $request->get('slug');


        $ressources = [];

        foreach($ressourceRepository->findBy([], ['createdAt' => 'DESC']) as $ressource){

            if($ressourceRepository->findOneBy(['id' => $ressource->getId()])->getRessourceCategory()->getSlug() == $category) {
                $ressources[] = $ressourceRepository->findOneBy(['id' => $ressource->getId()]);
            } 
        }

        // dd($ressources);

        return $this->render('admin/ressource/index_by_category.html.twig', [
            'ressources' => $ressources,
        ]);
    }


    
    
    /**
     * @Route("/admin/ressource/add", name="admin_ressource_add")
     */
    public function ressourceAdd(Request $request): Response
    {
        $ressource = new Ressource();
        $form = $this->createForm(RessourceFormType::class, $ressource);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form->get('ressourceCategory')->getData();
            $ressource->setUser($this->getUser());
            $ressource->setActive(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($ressource);
            $em->flush();
            return $this->redirectToRoute('admin_home',);
        }

        return $this->render('admin/ressource/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    



    /**
     * @Route("/admin/ressource/update/{id}", name="admin_ressource_update", requirements={"id"="\d+"})
     */
    public function ressourceUpdate(Ressource $ressource, Request $request): Response
    {
        // dd($ressource->getRessourceCategory()->getName());

        $form = $this->createForm(RessourceFormType::class, $ressource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ressource);
            $em->flush();

            $this->addFlash('success', 'Votre ressource a été modifié avec succes !');

            return $this->redirectToRoute('admin_ressource_by_category_index', ['slug' => $ressource->getRessourceCategory()->getSlug()]);
        }

        return $this->render('admin/ressource/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/ressource/activate/{id}", name="admin_ressource_activate", requirements={"id"="\d+"})
     */
    public function ressourceActivate(Ressource $ressource): Response
    {
        // dd($ressource);
        $ressource->setActive( ($ressource->getActive()) ?  false : true );

        $em = $this->getDoctrine()->getManager();
        $em->persist($ressource);
        $em->flush();

        return new Response('true');
    }



    /**
     * @Route("/admin/ressource/delete/{id}", name="admin_ressource_delete", requirements={"id"="\d+"})
     */
    public function ressourceDelete(Ressource $ressource): Response
    {
        // dd($ressource);
        // $ressource->setActive( ($ressource->getActive()) ?  false : true );

        $em = $this->getDoctrine()->getManager();
        $em->remove($ressource);
        $em->flush();

        $this->addFlash('success', 'Votre ressource a été supprimé avec succes !');

        return $this->redirectToRoute('admin_home');
    }
}
