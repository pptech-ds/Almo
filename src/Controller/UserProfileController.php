<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Repository\RessourceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class UserProfileController extends AbstractController
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
    
        return $this->render('user_profile/index.html.twig', [
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
        // $form->add('hospital', ChoiceType::class, [
        //         'choices' => [
        //             'Hopital 1' => 'Hopital 1',
        //             'Hopital 2' => 'Hopital 2',
        //             'Hopital 3' => 'Hopital 3'
        //         ],
        //         'expanded'  => true,
        //         'multiple' => true,
        //         'label' => 'Hopital'
        //     ])
        //     ->add('doctor', ChoiceType::class, [
        //         'choices' => [
        //             'Doctor 1' => 'Doctor 1',
        //             'Doctor 2' => 'Doctor 2',
        //             'Doctor 3' => 'Doctor 3'
        //         ],
        //         'expanded'  => true,
        //         'multiple' => true,
        //         'label' => 'Medecin'
        //     ])
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

        return $this->render('user_profile/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
