<?php

namespace App\Controller\Admin;

use App\Entity\Speciality;
use App\Form\SpecialityFormType;
use App\Repository\SpecialityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SpecialityController extends AbstractController
{
    /**
     * @Route("/admin/speciality", name="admin_speciality_index")
     */
    public function specialityIndex(SpecialityRepository $specialityRepository): Response
    {
        return $this->render('admin/speciality/index.html.twig', [
            'specialities' => $specialityRepository->findAll(),
        ]);
    }


    /**
     * @Route("/admin/speciality/add", name="admin_speciality_add")
     */
    public function specialityAdd(Request $request): Response
    {
        $ressource = new Speciality();
        $form = $this->createForm(SpecialityFormType::class, $ressource);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ressource);
            $em->flush();

            $this->addFlash('success', 'Votre specialité a été modifié avec succes !');

            return $this->redirectToRoute('admin_speciality_index',);
        }

        return $this->render('admin/speciality/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
