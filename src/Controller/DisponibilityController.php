<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Speciality;
use App\Entity\Disponibility;
use App\Repository\SpecialityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DisponibilityController extends AbstractController
{
    /**
     * @Route("/disponibility", name="disponibility_index")
     */
    public function disponibilityIndex(UserInterface $user): Response
    {
        // dd($user->getDisponibilities());
        return $this->render('disponibility/index.html.twig', [
            'disponibilities' => $user->getDisponibilities(),
        ]);
    }



    /**
     * @Route("/disponibility/update/{id}", name="disponibility_update", requirements={"id"="\d+"})
     */
    public function disponibilityUpdate(Disponibility $disponibility, Request $request): Response
    {
        $form = $this->createForm(DisponibilityFormType::class, $disponibility);
        // $form->add('Envoyer', SubmitType::class)
            // ;
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
