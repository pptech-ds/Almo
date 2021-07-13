<?php

namespace App\Controller;

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

/**
* @Route("/admin", name="admin_")
*/
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(RessourceRepository $ressourceRepository): Response
    {
        $ressources = $ressourceRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'ressources' => $ressources,
        ]);
    }




    /**
     * @Route("/ressource_category/add", name="ressource_category_add")
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
            return $this->redirectToRoute('admin_ressource_category_add');
        }

        return $this->render('admin/ressource_category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/ressource_category/", name="category_index")
     */
    public function indexCategory(RessourceCategoryRepository $ressourceCategoryRepository): Response
    {
        return $this->render('admin/ressource_category/index.html.twig', [
            'ressourceCategories' => $ressourceCategoryRepository->findAll(),
        ]);
    }



    /**
     * @Route("/ressource/add", name="ressource_add")
     */
    public function addRessource(Request $request): Response
    {
        $ressource = new Ressource();
        $form = $this->createForm(RessourceFormType::class, $ressource);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ressource->setUser($this->getUser());
            $ressource->setActive(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($ressource);
            $em->flush();
            return $this->redirectToRoute('admin_ressource_add');
        }

        return $this->render('admin/ressource/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
