<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Speciality;
use App\Entity\Disponibility;
use App\Repository\UserRepository;
use App\Repository\SpecialityRepository;
use App\Repository\DisponibilityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Admin\AdminDisponibilityFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\Admin\AdminDisponibilityAsignFormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DisponibilityController extends AbstractController
{
    /**
     * @Route("/admin/disponibility/add", name="admin_disponibility_add")
     */
    public function addDisponibility(Request $request, UserRepository $userRepository): Response
    {
        $usersPro = $userRepository->findByRole('ROLE_PRO');
        // dd($usersPro);

        $disponibility = new Disponibility();
        $form = $this->createForm(AdminDisponibilityFormType::class, $disponibility);
        $form->add('createdBy', EntityType::class,[
            'class' => User::class,
            'choices' => $usersPro
        ])
        // ->add('reservedBy', EntityType::class)
        ->add('Enregistrer', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $disponibility->setUser($this->getUser());
            // $webinar->setActive(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($disponibility);
            $em->flush();

            $this->addFlash('success', 'La disponibilité a été ajouté avec success');

            return $this->redirectToRoute('admin_disponibility_add');
        }

        return $this->render('admin/disponibility/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/admin/disponibility/update/{id}", name="admin_disponibility_update")
     */
    public function updateDisponibility(Disponibility $disponibility,Request $request): Response
    {
        // $disponibility = new Disponibility();
        $form = $this->createForm(AdminDisponibilityFormType::class, $disponibility);
        $form->add('Enregistrer', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $disponibility->setUser($this->getUser());
            // $webinar->setActive(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($disponibility);
            $em->flush();

            $this->addFlash('success', 'La disponibilité a été mis à jour avec success');

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('admin/disponibility/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/disponibility/asign", name="admin_disponibility_asign")
     */
    public function asignDisponibility(DisponibilityRepository $disponibilityRepository,UserRepository $userRepository, Request $request): Response
    {
        // $usersPro = $userRepository->findByRole('ROLE_PRO');
        $usersPatient = $userRepository->findByRole('ROLE_PATIENT');

        $disponibilities = $disponibilityRepository->findBy([
            'reservedBy' => null
        ]);
        
        // $disponibility = new Disponibility();
        $form = $this->createForm(AdminDisponibilityAsignFormType::class);
        $form->add('name', EntityType::class, [
            'class' => Disponibility::class,
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
            
            $disponibility = $form->get('name')->getData();
            $patient = $form->get('reservedBy')->getData();
            $disponibility->setReservedBy($patient);
            // $webinar->setActive(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($disponibility);
            $em->flush();

            $this->addFlash('success', 'La disponibilité a été asignée avec success');

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('admin/disponibility/asign.html.twig', [
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

        return $this->render('admin/speciality/disponibility_by_name.html.twig', [
            'usersBySpeciality' => $arrayDispo,
        ]);
    }



    
}
