<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Report;
use App\Form\UserFormType;
use App\Entity\Appointment;
use App\Repository\UserRepository;
use App\Form\AppointmentFormType;
use App\Repository\RessourceRepository;
use App\Repository\AppointmentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class UserController extends AbstractController
{
    
    /**
     * @Route("/user/profile", name="user_profile")
     */
    public function userProfile(UserInterface $user): Response
    {
        // dd($user->getFullName());

        // dd($user->setFullName($user->getCivility(), $user->getFirstname(), $user->getLastname()));



        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }


    
    /**
     * @Route("/user/update", name="user_update")
     */
    public function userUpdate(UserInterface $user, Request $request): Response
    {
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre utilisateur a été modifié avec succes !');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/user/biography/{id}", name="user_biography", requirements={"id"="\d+"})
     */
    public function userBio(UserRepository $userRepository, AppointmentRepository $appointmentRepository, Request $request): Response
    {
        $user = $userRepository->findOneBy(array('id' => $request->get('id')));

        $disponibilities = [];

        if(($user->getRoles()[0] == 'ROLE_PRO') || ($user->getRoles()[0] == 'ROLE_DOC')) {  
            foreach($appointmentRepository->findBy(['createdBy' => $user]) as $appointment){
                if($appointment->getReservedBy() == null) {
                    $disponibilities[] = $appointment;
                } 
            }
        } 

        return $this->render('user/biography.html.twig', [
            'user' => $user,
            'disponibilities' => $disponibilities
        ]);
    }


    
}
