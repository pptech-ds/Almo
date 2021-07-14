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


class RessourceCategoryController extends AbstractController
{
    
    /**
     * @Route("/admin/ressource_category", name="admin_ressource_category_index")
     */
    public function indexRessourceCategory(RessourceCategoryRepository $ressourceCategoryRepository): Response
    {
        return $this->render('admin/ressource_category/index.html.twig', [
            'ressourceCategories' => $ressourceCategoryRepository->findAll(),
        ]);
    }


    
    /**
     * @Route("/admin/ressource_category/add", name="admin_ressource_category_add")
     */
    public function addRessourceCategory(Request $request): Response
    {
        $ressourceCategory = new RessourceCategory();
        $form = $this->createForm(RessourceCategoryFormType::class, $ressourceCategory);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ressourceCategory);
            $em->flush();
            return $this->redirectToRoute('admin_ressource_category_index');
        }

        return $this->render('admin/ressource_category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    
    /**
     * @Route("/admin/ressource_category/update/{id}", name="admin_ressource_category_update", requirements={"id"="\d+"})
     */
    public function updateRessourceCategory(RessourceCategory $ressourceCategory, Request $request): Response
    {
        $form = $this->createForm(RessourceCategoryFormType::class, $ressourceCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ressourceCategory);
            $em->flush();

            $this->addFlash('success', 'Votre Cetegorie a été modifié avec succes !');

            return $this->redirectToRoute('admin_ressource_category_index');
        }

        return $this->render('admin/ressource_category/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/ressource_category/delete/{id}", name="admin_ressource_category_delete", requirements={"id"="\d+"})
     */
    public function deleteRessourceCategory(RessourceCategory $ressourceCategory): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($ressourceCategory);
        $em->flush();

        $this->addFlash('success', 'Votre categorie a été supprimé avec succes !');

        return $this->redirectToRoute('admin_ressource_category_index');
    }
}
