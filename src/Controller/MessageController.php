<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Appointment;
use App\Form\MessageFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     */
    public function index(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }


    /**
     * @Route("/message/appointment/{id}", name="message_appointment")
     */
    public function messageAppointment(Request $request, Appointment $appointment): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageFormType::class, $message);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($this->getUser());
            $message->setAppointment($appointment);
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash('success', 'Votre message a été envoyé avec success');

            return $this->redirectToRoute('appointment_future');
        }

        return $this->render('message/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
