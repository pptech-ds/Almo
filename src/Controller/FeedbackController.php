<?php

namespace App\Controller;

use App\Entity\Webinar;
use App\Entity\Feedback;
use App\Entity\Appointment;
use App\Form\FeedbackFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FeedbackController extends AbstractController
{
    /**
     * @Route("/feedback", name="feedback")
     */
    public function index(): Response
    {
        return $this->render('feedback/index.html.twig', [
            'controller_name' => 'FeedbackController',
        ]);
    }


    /**
     * @Route("/feedback/appointment/{id}", name="feedback_appointment")
     */
    public function feedbackAppointment(Request $request, Appointment $appointment): Response
    {
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackFormType::class, $feedback);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $feedback->setSender($this->getUser());
            $feedback->setAppointment($appointment);
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedback);
            $em->flush();

            $this->addFlash('success', 'Votre avis a été envoyé avec success');

            return $this->redirectToRoute('appointment_past');
        }

        return $this->render('feedback/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/feedback/webinar/{id}", name="feedback_webinar")
     */
    public function feedbackwebinar(Request $request, Webinar $webinar): Response
    {
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackFormType::class, $feedback);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $feedback->setSender($this->getUser());
            $feedback->setWebinar($webinar);
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedback);
            $em->flush();

            $this->addFlash('success', 'Votre avis a été envoyé avec success');

            return $this->redirectToRoute('webinar_past');
        }

        return $this->render('feedback/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
