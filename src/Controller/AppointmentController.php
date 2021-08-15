<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Speciality;
use App\Entity\Appointment;
use App\Form\AppointmentFormType;
use App\Repository\SpecialityRepository;
use App\Repository\AppointmentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppointmentController extends AbstractController
{
    /**
     * @Route("/appointment", name="appointment_index")
     */
    public function appointmentIndex(UserInterface $user, AppointmentRepository $appointmentRepository): Response
    {
        // dd($user->getAppointments());

        // dd($user->getDisponibilities());

        $reservations = [];
        $reservationsRender = [];
        $disponibilities = [];

        if(($user->getRoles()[0] == 'ROLE_PRO') || ($user->getRoles()[0] == 'ROLE_DOC')) {  
            foreach($appointmentRepository->findBy(['createdBy' => $user]) as $appointment){
                if($appointment->getReservedBy() != null) {
                    $reservations[] = $appointment;
                } else {
                    $disponibilities[] = $appointment;
                }
            }
        } else {
            $reservations[] = $user->getReservations();
        }


        if(($user->getRoles()[0] == 'ROLE_PRO') || ($user->getRoles()[0] == 'ROLE_DOC')) {  
            $reservationsRender = $reservations;
        } else {
            $reservationsRender = $reservations[0];
        }


        // dd($reservations);
        // dd($disponibilities);

        return $this->render('appointment/index.html.twig', [
            'reservations' => $reservationsRender,
            'disponibilities' => $disponibilities,
        ]);
    }



    /**
     * @Route("/appointment/future", name="appointment_future")
     */
    public function appointmentFuture(UserInterface $user, AppointmentRepository $appointmentRepository): Response
    {
        // dd($user->getAppointments());

        // dd($user->getDisponibilities());

        $reservations = [];
        $reservationsRender = [];
        $disponibilities = [];

        if(($user->getRoles()[0] == 'ROLE_PRO') || ($user->getRoles()[0] == 'ROLE_DOC')) {  
            foreach($appointmentRepository->findBy(['createdBy' => $user]) as $appointment){
                if($appointment->getReservedBy() != null) {
                    $reservations[] = $appointment;
                } else {
                    $disponibilities[] = $appointment;
                }
            }
        } else {
            $reservations[] = $user->getReservations();
        }


        if(($user->getRoles()[0] == 'ROLE_PRO') || ($user->getRoles()[0] == 'ROLE_DOC')) {  
            $reservationsRender = $reservations;
        } else {
            $reservationsRender = $reservations[0];
        }


        $reservationsFuture = [];
        $currentDate = new DateTime(date('Y-m-d H:m:s'));

        foreach($reservationsRender as $reservationRender){
            $interval = $currentDate->diff($reservationRender->getStartTime());
            if($interval->format('%R%a') > 0){
                $reservationsFuture[] = $reservationRender;
            }
        }

        return $this->render('appointment/future.html.twig', [
            'reservations' => $reservationsFuture,
            'disponibilities' => $disponibilities,
        ]);
    }



    /**
     * @Route("/appointment/past", name="appointment_past")
     */
    public function appointmentPast(UserInterface $user, AppointmentRepository $appointmentRepository): Response
    {
        // dd($user->getAppointments());

        // dd($user->getDisponibilities());

        $reservations = [];
        $reservationsRender = [];
        $disponibilities = [];

        if(($user->getRoles()[0] == 'ROLE_PRO') || ($user->getRoles()[0] == 'ROLE_DOC')) {  
            foreach($appointmentRepository->findBy(['createdBy' => $user]) as $appointment){
                if($appointment->getReservedBy() != null) {
                    $reservations[] = $appointment;
                } else {
                    $disponibilities[] = $appointment;
                }
            }
        } else {
            $reservations[] = $user->getReservations();
        }


        if(($user->getRoles()[0] == 'ROLE_PRO') || ($user->getRoles()[0] == 'ROLE_DOC')) {  
            $reservationsRender = $reservations;
        } else {
            $reservationsRender = $reservations[0];
        }

        // $origin = new DateTime('2009-10-11');
        // $target = new DateTime('2009-10-13');
        // $interval = $origin->diff($target);
        // echo $interval->format('%R%a days');

        // dd($reservations[0][0]->getStartTime());
        // dd($interval->format('%R%a days'));
        // dd(date('Y-m-d H:m:s'));

        // $origin = new DateTime(date('Y-m-d H:m:s'));
        // // $target = new DateTime('2009-10-13');
        // $interval = $origin->diff($reservationsRender[0]->getStartTime());

        // dd($interval->format('%R%a'));

        // if($interval->format('%R%a') < 0) {
        //     dd($interval->format('%R%a'));
        // } else {
        //     dd('over 0');
        // }

        $reservationsPast = [];
        $currentDate = new DateTime(date('Y-m-d H:m:s'));

        foreach($reservationsRender as $reservationRender){
            $interval = $currentDate->diff($reservationRender->getStartTime());
            if($interval->format('%R%a') < 0){
                $reservationsPast[] = $reservationRender;
            }
        }

        return $this->render('appointment/past.html.twig', [
            'reservations' => $reservationsPast,
            'disponibilities' => $disponibilities,
        ]);
    }



    /**
     * @Route("/appointment/view/{id}", name="appointment_view", requirements={"id"="\d+"})
     */
    public function appointmentView(Request $request, AppointmentRepository $appointmentRepository): Response
    {
        
        return $this->render('appointment/view.html.twig', [
            'reservation' => $appointmentRepository->findOneBy(['id' => $request->get('id')]),
        ]);
    }



    /**
     * @Route("/appointment/add/{id}", name="appointment_add", requirements={"id"="\d+"})
     */
    public function appointmentAdd(Request $request, UserInterface $user, AppointmentRepository $appointmentRepository): Response
    {
        // dd($request->get('id'));

        // dd($user);

        // dd($appointmentRepository->findOneBy(['id' => $request->get('id')]));

        $user->addReservation($appointmentRepository->findOneBy(['id' => $request->get('id')]));

        // dd($user);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Votre reservation a été faite avec success');

        return $this->redirectToRoute('appointment_index');
    }



    /**
     * @Route("/appointment/create", name="appointment_create")
     */
    public function appointmentCreate(UserInterface $user, AppointmentRepository $appointmentRepository, Request $request): Response
    {
        $appointment = new Appointment();
        $appointment->setCreatedBy($user);
        $form = $this->createForm(AppointmentFormType::class, $appointment);
        $form->add('Enregistrer', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($appointment);
            $em->flush();

            $this->addFlash('success', 'La disponibilité a été ajouté avec success');

            return $this->redirectToRoute('appointment_index');
        }

        return $this->render('appointment/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/appointment/update/{id}", name="appointment_update", requirements={"id"="\d+"})
     */
    public function appointmentUpdate(UserInterface $user, Appointment $appointment, Request $request): Response
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

            return $this->redirectToRoute('appointment_index');
        }

        return $this->render('appointment/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/appointment/delete/{id}", name="appointment_delete", requirements={"id"="\d+"})
     */
    public function appointmentDelete(Appointment $appointment): Response
    {
        // dd($ressource);
        // $ressource->setActive( ($ressource->getActive()) ?  false : true );

        $em = $this->getDoctrine()->getManager();
        $em->remove($appointment);
        $em->flush();

        $this->addFlash('success', 'Votre disponibilité a été supprimé avec succes !');

        return $this->redirectToRoute('appointment_index');
    }
}
