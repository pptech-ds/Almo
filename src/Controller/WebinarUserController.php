<?php

namespace App\Controller;

use App\Entity\Webinar;
use App\Entity\Ressource;
use App\Repository\WebinarRepository;
use App\Repository\RessourceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ContainerIfCTfyJ\PaginatorInterface_82dac15;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WebinarUserController extends AbstractController
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
     * @Route("/webinar/{slug}", name="webinar_view", methods={"GET"})
     */
    public function view(Webinar $webinar): Response
    {
    
        return $this->render('webinar/view.html.twig', [
            'webinar' => $webinar,
        ]);
    }
}
