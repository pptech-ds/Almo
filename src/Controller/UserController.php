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
    public function userProfile(UserRepository $userRepository, UserInterface $user): Response
    {
        return $this->render('user/profile.html.twig', [
            'user' => $userRepository->findOneBy([
                'id' => $user->getId()
            ]),
        ]);
    }


    
    /**
     * @Route("/user/update/{id}", name="user_update", requirements={"id"="\d+"})
     */
    public function updateUser(User $user, Request $request): Response
    {
        $form = $this->createForm(UserFormType::class, $user);
        $form->add('Envoyer', SubmitType::class)
            ;
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
