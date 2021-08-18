<?php

namespace App\Controller;

use DateTime;
use App\Entity\Webinar;
use App\Entity\Ressource;
use App\Entity\WebinarQuestions;
use App\Repository\WebinarRepository;
use App\Form\WebinarQuestionsFormType;
use App\Repository\RessourceRepository;
use App\Repository\WebinarQuestionsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ContainerIfCTfyJ\PaginatorInterface_82dac15;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WebinarController extends AbstractController
{
    /**
     * @Route("/webinar", name="webinar_index")
     */
    public function index(Request $request, PaginatorInterface $paginator, WebinarRepository $webinarRepository): Response
    {
        
        $webinars = $webinarRepository->findBy(
            [],
            ['createdAt' => 'desc']
        );

        $webinars_paginated = $paginator->paginate(
            $webinars,
            $request->query->getInt('page', 1),
            9
        );
   
        return $this->render('webinar/index.html.twig', [
            'webinars' => $webinars_paginated,
        ]);
    }



    /**
     * @Route("/webinar/future", name="webinar_future")
     */
    public function webinarFuture(WebinarRepository $webinarRepository): Response
    {
        $webinarsFuture = [];
        $currentDate = new DateTime(date('Y-m-d H:m:s'));

        foreach($webinarRepository->findAll() as $webinarFuture){
            $interval = $currentDate->diff($webinarFuture->getStartTime());
            if($interval->format('%R%a') > 0){
                $webinarsFuture[] = $webinarFuture;
            }
        }
   
        return $this->render('webinar/future.html.twig', [
            'webinars' => $webinarsFuture,
        ]);
    }



    /**
     * @Route("/webinar/past", name="webinar_past")
     */
    public function webinarPast(Request $request, PaginatorInterface $paginator, WebinarRepository $webinarRepository): Response
    {
        
        $webinarsPast = [];
        $currentDate = new DateTime(date('Y-m-d H:m:s'));

        foreach($webinarRepository->findAll() as $webinarFuture){
            $interval = $currentDate->diff($webinarFuture->getStartTime());
            if($interval->format('%R%a') < 0){
                $webinarsPast[] = $webinarFuture;
            }
        }
   
        return $this->render('webinar/past.html.twig', [
            'webinars' => $webinarsPast,
        ]);
    }


    /**
     * @Route("/webinar/{slug}", name="webinar_view")
     */
    public function view(Request $request, Webinar $webinar, WebinarQuestionsRepository $webinarQuestionsRepository): Response
    {
       
        $webinarQuestions = new WebinarQuestions();
        $form = $this->createForm(WebinarQuestionsFormType::class, $webinarQuestions);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $webinarQuestions->setUser($this->getUser());
            $webinarQuestions->setWebinar($webinar);
            $em = $this->getDoctrine()->getManager();
            $em->persist($webinarQuestions);

            $webinar->addReservedBy($this->getUser());
            $em->persist($webinar);

            $em->flush();

            $this->addFlash('success', 'Vos questions ont été ajoutés avec succes !');
            
            // return $this->redirectToRoute('webinar_view',['slug' => $request->get('slug')]);
            return $this->redirectToRoute('webinar_future');
        }
    
        return $this->render('webinar/view.html.twig', [
            'webinar' => $webinar,
            'form' => $form->createView(),
            'webinarQuestions' => $webinarQuestionsRepository->findAll([
                'webinar' => $webinar
            ])
        ]);
    }
}
