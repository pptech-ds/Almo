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
     * @Route("/admin/ressource_category", name="admin_ressource_category_index")
     */
    public function indexCategory(RessourceCategoryRepository $ressourceCategoryRepository): Response
    {
        return $this->render('admin/ressource_category/index.html.twig', [
            'ressourceCategories' => $ressourceCategoryRepository->findAll(),
        ]);
    }

}
