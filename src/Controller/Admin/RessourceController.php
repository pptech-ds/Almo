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
     * @Route("/admin/ressource/add", name="admin_ressource_add")
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
            return $this->redirectToRoute('admin_ressource_index');
        }

        return $this->render('admin/ressource/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/admin/ressource", name="admin_ressource_index")
     */
    public function indexRessource(RessourceRepository $ressourceRepository): Response
    {
        $ressources = $ressourceRepository->findAll();

        return $this->render('admin/ressource/index.html.twig', [
            'ressources' => $ressources,
        ]);
    }
}
