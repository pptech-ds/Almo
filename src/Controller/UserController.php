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
     * @Route("/user/reservation/list", name="user_reservation_list")
     */
    public function listUserReservation(UserInterface $user): Response
    {
        
        return $this->render('user/reservation.html.twig', [
            'reservations' => $user->getReservations(),
        ]);
    }


    /**
     * @Route("/user/reservation/view/{id}", name="user_reservation_view", requirements={"id"="\d+"})
     */
    public function viewUserReservation(Request $request, DisponibilityRepository $disponibilityRepository): Response
    {
        
        return $this->render('user/reservation_view.html.twig', [
            'reservation' => $disponibilityRepository->findOneBy(['id' => $request->get('id')]),
        ]);
    }


    /**
     * @Route("/user/reservation/add/{id}", name="user_reservation_add", requirements={"id"="\d+"})
     */
    public function addUserReservation(Request $request, UserInterface $user, DisponibilityRepository $disponibilityRepository): Response
    {
        // dd($request->get('id'));

        // dd($user);

        // dd($disponibilityRepository->findOneBy(['id' => $request->get('id')]));

        $user->addReservation($disponibilityRepository->findOneBy(['id' => $request->get('id')]));

        // dd($user);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Votre reservation a été faite avec success');

        return $this->redirectToRoute('user_reservation_list');
    }


    /**
     * @Route("/user/profile/{id}", name="user_profile", requirements={"id"="\d+"})
     */
    public function userProfile(Request $request, UserRepository $userRepository): Response
    {
        // dd($request->get('id'));

        // dd($user);

        // dd($userRepository->findOneBy(['id' => $request->get('id')]));

        return $this->render('user/profile.html.twig', [
            'user' => $userRepository->findOneBy(['id' => $request->get('id')]),
        ]);
    }
}
