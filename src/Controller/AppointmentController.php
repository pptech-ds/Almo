<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Speciality;
use App\Entity\Appointment;
use App\Repository\SpecialityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppointmentController extends AbstractController
{
    /**
     * @Route("/appointment", name="appointment_index")
     */
    public function appointmentIndex(UserInterface $user): Response
    {
        // dd($user->getDisponibilities());
        return $this->render('appointment/index.html.twig', [
            'disponibilities' => $user->getDisponibilities(),
        ]);
    }



    /**
     * @Route("/appointment/update/{id}", name="appointment_update", requirements={"id"="\d+"})
     */
    public function appointmentUpdate(Appointment $appointment, Request $request): Response
    {
        $form = $this->createForm(AppointmentFormType::class, $appointment);
        // $form->add('Envoyer', SubmitType::class)
            // ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre utilisateur a été modifié avec succes !');

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
