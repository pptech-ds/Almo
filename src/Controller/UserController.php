<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Entity\Disponibility;
use App\Repository\UserRepository;
use App\Form\DisponibilityFormType;
use App\Repository\RessourceRepository;
use App\Repository\DisponibilityRepository;
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
     * @Route("/user", name="user_index")
     */
    public function index(UserRepository $userRepository, UserInterface $user): Response
    {
        // dd($user->getId());
        // dd($userRepository->findOneBy([
        //     'id' => $user->getId()
        // ]));
    
        return $this->render('user/index.html.twig', [
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



    /**
     * @Route("/user/disponibility", name="user_disponibility")
     */
    public function addUserDisponibility(Request $request): Response
    {
        // dd($this->getUser()->getEmail());
        $disponibility = new Disponibility();
        $form = $this->createForm(DisponibilityFormType::class, $disponibility);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $disponibility->setCreatedBy($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($disponibility);
            $em->flush();

            $this->addFlash('success', 'Votre disponibilité a été ajouté avec succes !');

            return $this->redirectToRoute('home');
        }

        return $this->render('user/disponibility.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/user/reservation", name="user_reservation")
     */
    public function addUserReservation(DisponibilityRepository $disponibilityRepository): Response
    {
        $disponibilities = $disponibilityRepository->findAll();
        
        return $this->render('user/reservation.html.twig', [
            'disponibilities' => $disponibilities,
        ]);
    }
}
