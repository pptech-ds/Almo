<?php

namespace App\Controller\Admin;

use App\Entity\Webinar;
use App\Form\WebinarFormType;
use App\Entity\WebinarCategory;
use App\Form\WebinarCategoryFormType;
use App\Repository\WebinarRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\WebinarCategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class WebinarCategoryController extends AbstractController
{
    
    /**
     * @Route("/admin/webinar_category", name="admin_webinar_category_index")
     */
    public function indexWebinarCategory(WebinarCategoryRepository $webinarCategoryRepository): Response
    {
        return $this->render('admin/webinar_category/index.html.twig', [
            'webinarCategories' => $webinarCategoryRepository->findAll(),
        ]);
    }


    
    /**
     * @Route("/admin/webinar_category/add", name="admin_webinar_category_add")
     */
    public function addWebinarCategory(Request $request): Response
    {
        $webinarCategory = new WebinarCategory();
        $form = $this->createForm(WebinarCategoryFormType::class, $webinarCategory);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($webinarCategory);
            $em->flush();
            return $this->redirectToRoute('admin_webinar_category_index');
        }

        return $this->render('admin/webinar_category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    
    /**
     * @Route("/admin/webinar_category/update/{id}", name="admin_webinar_category_update", requirements={"id"="\d+"})
     */
    public function updateWebinarCategory(WebinarCategory $webinarCategory, Request $request): Response
    {
        $form = $this->createForm(WebinarCategoryFormType::class, $webinarCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($webinarCategory);
            $em->flush();

            $this->addFlash('success', 'Votre Cetegorie a été modifié avec succes !');

            return $this->redirectToRoute('admin_webinar_category_index');
        }

        return $this->render('admin/webinar_category/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/webinar_category/delete/{id}", name="admin_webinar_category_delete", requirements={"id"="\d+"})
     */
    public function deleteWebinarCategory(WebinarCategory $webinarCategory): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($webinarCategory);
        $em->flush();

        $this->addFlash('success', 'Votre categorie a été supprimé avec succes !');

        return $this->redirectToRoute('admin_webinar_category_index');
    }
}
