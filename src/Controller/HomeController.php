<?php

namespace App\Controller;

use App\Entity\Ressource;
use App\Entity\RessourceCategory;
use App\Repository\WebinarRepository;
use App\Repository\RessourceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(RessourceRepository $ressourceRepository, WebinarRepository $webinarRepository): Response
    {
        
        // $documentCategory = new RessourceCategory();
        // $documentCategory->setName('Document');
        // $documents = $ressourceRepository->findBy(
        //     ['ressourceCategory' => $ressource->setRessourceCategory($documentCategory)],
        //     ['createdAt' => 'desc'],
        //     3
        // );

        $documents = $ressourceRepository->findLastRessourcesByCategory('Document', 6);
        // dd($documents);

        $articles = $ressourceRepository->findLastRessourcesByCategory('Article', 6);
        // dd($articles);

        $webinars = $webinarRepository->findBy(
            [],
            ['createdAt' => 'desc'],
            6
        );


        return $this->render('home/index.html.twig', [
            'documents' => $documents,
            'articles' => $articles,
            'webinars' => $webinars,
        ]);
    }

    /**
     * @Route("/error_405", name="error_405")
     */
    public function error405(): Response
    {
        return $this->render('error/405.html.twig', [
            
        ]);
    }
}
