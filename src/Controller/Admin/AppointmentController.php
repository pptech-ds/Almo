<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Speciality;
use App\Entity\Appointment;
use App\Repository\UserRepository;
use App\Repository\SpecialityRepository;
use App\Repository\AppointmentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Admin\AdminAppointmentFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\Admin\AdminAppointmentAsignFormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppointmentController extends AbstractController
{
    /**
     * @Route("/admin/appointment/add", name="admin_appointment_add")
     */
    public function addAppointment(Request $request, UserRepository $userRepository): Response
    {
        $usersPro = $userRepository->findByRole('ROLE_PRO');
        // dd($usersPro);

        $appointment = new Appointment();
        $form = $this->createForm(AdminAppointmentFormType::class, $appointment);
        $form->add('createdBy', EntityType::class,[
            'class' => User::class,
            'choices' => $usersPro
        ])
        // ->add('reservedBy', EntityType::class)
        ->add('Enregistrer', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $appointment->setUser($this->getUser());
            // $webinar->setActive(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($appointment);
            $em->flush();

            $this->addFlash('success', 'La disponibilité a été ajouté avec success');

            return $this->redirectToRoute('admin_appointment_add');
        }

        return $this->render('admin/appointment/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/admin/appointment/update/{id}", name="admin_appointment_update")
     */
    public function updateAppointment(Appointment $appointment,Request $request): Response
    {
        // $appointment = new Appointment();
        $form = $this->createForm(AdminAppointmentFormType::class, $appointment);
        $form->add('Enregistrer', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $appointment->setUser($this->getUser());
            // $webinar->setActive(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($appointment);
            $em->flush();

            $this->addFlash('success', 'La disponibilité a été mis à jour avec success');

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('admin/appointment/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/appointment/asign", name="admin_appointment_asign")
     */
    public function asignAppointment(AppointmentRepository $appointmentRepository,UserRepository $userRepository, Request $request): Response
    {
        // $usersPro = $userRepository->findByRole('ROLE_PRO');
        $usersPatient = $userRepository->findByRole('ROLE_PATIENT');

        $disponibilities = $appointmentRepository->findBy([
            'reservedBy' => null
        ]);
        
        // $appointment = new Appointment();
        $form = $this->createForm(AdminAppointmentAsignFormType::class);
        $form->add('name', EntityType::class, [
            'class' => Appointment::class,
            'choices' => $disponibilities,
            'label' => 'Disponibilité'
        ])
            ->add('reservedBy', EntityType::class, [
                'class' => User::class,
                'choices' => $usersPatient,
                'label' => 'Patient'
        ]);
        $form->add('Enregistrer', SubmitType::class);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $appointment = $form->get('name')->getData();
            $patient = $form->get('reservedBy')->getData();
            $appointment->setReservedBy($patient);
            // $webinar->setActive(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($appointment);
            $em->flush();

            $this->addFlash('success', 'La disponibilité a été asignée avec success');

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('admin/appointment/asign.html.twig', [
            'form' => $form->createView(),
        ]);
    }




    /**
     * @Route("/admin/speciality/name/{slug}", name="admin_speciality_by_name")
     */
    public function disponibilitiesBySpeciality(SpecialityRepository $specialityRepository, Request $request): Response
    {
        
        // $speciality = $request->get('name');

        $usersBySpeciality = $specialityRepository->findBy(['slug' => $request->get('slug')])[0]->getUsers();

        $arrayDispo = [];

        for($j = 0 ; $j< count($usersBySpeciality); $j++){
            
            // for($i = 0; $i< count($usersBySpeciality[$j]->getDisponibilities()); $i++) {

            //     $arrayDispo[] = ($usersBySpeciality[$j]->getDisponibilities()[$i]);
            // }

            if(count($usersBySpeciality[$j]->getDisponibilities()) > 0){
                $arrayDispo[] = $usersBySpeciality[$j];
            }
        }

        return $this->render('admin/speciality/appointment_by_name.html.twig', [
            'usersBySpeciality' => $arrayDispo,
        ]);
    }



    
}
