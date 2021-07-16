<?php

namespace App\Controller;

use App\Entity\Ressource;
use App\Repository\RessourceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ContainerIfCTfyJ\PaginatorInterface_82dac15;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DocumentController extends AbstractController
{
    /**
     * @Route("/document", name="document_index")
     */
    public function index(Request $request, PaginatorInterface $paginator, RessourceRepository $ressourceRepository): Response
    {
        $documents = $ressourceRepository->findAllRessourcesByCategory('Document');

        $documents_paginated = $paginator->paginate(
            $documents,
            $request->query->getInt('page', 1),
            9
        );
   
        return $this->render('document/index.html.twig', [
            'documents' => $documents_paginated,
        ]);
    }


    /**
     * @Route("/document/{slug}", name="document_view", methods={"GET"})
     */
    public function view(Ressource $ressource): Response
    {
    
        return $this->render('document/view.html.twig', [
            'document' => $ressource,
        ]);
    }
}
