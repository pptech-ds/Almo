<?php

namespace App\Controller;

use App\Repository\RessourceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(RessourceRepository $ressourceRepository): Response
    {
        // $ressources = $ressourceRepository->findAll();
        // dd($ressources);
        $documents = $ressourceRepository->findLastRessourcesByCategory('Document', 6);
        // dd($documents);

        $articles = $ressourceRepository->findLastRessourcesByCategory('Article', 6);
        // dd($articles);


        return $this->render('home/index.html.twig', [
            'documents' => $documents,
            'articles' => $articles,
        ]);
    }
}
