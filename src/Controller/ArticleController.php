<?php

namespace App\Controller;

use App\Repository\RessourceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ContainerIfCTfyJ\PaginatorInterface_82dac15;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index(Request $request, PaginatorInterface $paginator, RessourceRepository $ressourceRepository): Response
    {
        
        // $articles = $ressourceRepository->findAll();
        $articles = $ressourceRepository->findBy([],['createdAt' => 'desc']);

        $articles_paginated = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            9
        );
   
        return $this->render('article/index.html.twig', [
            'articles' => $articles_paginated,
        ]);
    }
}
