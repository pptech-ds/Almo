<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Webinar;
use App\Entity\Ressource;
use App\Form\WebinarFormType;
use App\Entity\WebinarCategory;
use App\Form\RessourceFormType;
use App\Repository\UserRepository;
use App\Form\WebinarCategoryFormType;
use App\Repository\WebinarRepository;
use App\Form\Admin\AdminWebinarAsignFormType;
use App\Repository\WebinarCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class WebinarController extends AbstractController
{
    
    /**
     * @Route("/admin/webinar", name="admin_webinar_index")
     */
    public function indexWebinar(WebinarRepository $webinarRepository): Response
    {
        $webinars = $webinarRepository->findAll();

        return $this->render('admin/webinar/index.html.twig', [
            'webinars' => $webinars,
        ]);
    }


    
    /**
     * @Route("/admin/webinar/add", name="admin_webinar_add")
     */
    public function addWebinar(Request $request): Response
    {
        $webinar = new Webinar();
        $form = $this->createForm(WebinarFormType::class, $webinar);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $webinar->setUser($this->getUser());
            $webinar->setActive(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($webinar);
            $em->flush();
            return $this->redirectToRoute('admin_webinar_index');
        }

        return $this->render('admin/webinar/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }




    
    /**
     * @Route("/admin/webinar/update/{id}", name="admin_webinar_update", requirements={"id"="\d+"})
     */
    public function updateWebinar(Webinar $webinar, Request $request): Response
    {
        $form = $this->createForm(WebinarFormType::class, $webinar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($webinar);
            $em->flush();

            $this->addFlash('success', 'Votre webinar a été modifié avec succes !');

            return $this->redirectToRoute('admin_webinar_index');
        }

        return $this->render('admin/webinar/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/webinar/activate/{id}", name="admin_webinar_activate", requirements={"id"="\d+"})
     */
    public function activateWebinar(Webinar $webinar): Response
    {
        // dd($Webinar);
        $webinar->setActive( ($webinar->getActive()) ?  false : true );

        $em = $this->getDoctrine()->getManager();
        $em->persist($webinar);
        $em->flush();

        return new Response('true');
    }



    /**
     * @Route("/admin/webinar/delete/{id}", name="admin_webinar_delete", requirements={"id"="\d+"})
     */
    public function deleteWebinar(Webinar $webinar): Response
    {
        // dd($Webinar);
        // $Webinar->setActive( ($Webinar->getActive()) ?  false : true );

        $em = $this->getDoctrine()->getManager();
        $em->remove($webinar);
        $em->flush();

        $this->addFlash('success', 'Votre webinar a été supprimé avec succes !');

        return $this->redirectToRoute('admin_webinar_index');
    }



    /**
     * @Route("/admin/webinar/asign", name="admin_webinar_asign")
     */
    public function webinarAsign(WebinarRepository $webinarRepository,UserRepository $userRepository, Request $request): Response
    {
        // $usersPro = $userRepository->findByRole('ROLE_PRO');
        $usersPatient = $userRepository->findByRole('ROLE_PATIENT');

        $webinars = $webinarRepository->findAll();
        
        // $appointment = new Appointment();
        $form = $this->createForm(AdminWebinarAsignFormType::class);
        $form->add('reservedBy', EntityType::class, [
                'class' => User::class,
                'choices' => $usersPatient,
                'label' => 'Patient'
        ]);
        $form->add('Enregistrer', SubmitType::class);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            dd($form->get('reservedBy'));
            
            // $webinar = $form->get('title')->getData();
            // $patient = $form->get('reservedBy')->getData();
            // dd($patient);
            // $webinar->addReservedBy([$patient]);
            // // $webinar->setActive(false);
            // $em = $this->getDoctrine()->getManager();
            // $em->persist($webinar);
            // $em->flush();

            $this->addFlash('success', 'Le webinar a été asignée avec success');

            return $this->redirectToRoute('admin_webinar_asign');
        }

        return $this->render('admin/webinar/asign.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
