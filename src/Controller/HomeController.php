<?php

namespace App\Controller;

use App\Entity\Ressource;
use App\Entity\RessourceCategory;
use App\Repository\WebinarRepository;
use App\Repository\RessourceRepository;
use App\Repository\InformationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(RessourceRepository $ressourceRepository, WebinarRepository $webinarRepository, InformationRepository $informationRepository, AuthenticationUtils $authenticationUtils): Response
    {
        $documents = $ressourceRepository->findLastRessourcesByCategory('Document', 3);
        // dd($documents);

        $articles = $ressourceRepository->findLastRessourcesByCategory('Article', 3);
        // dd($articles);

        $webinars = $webinarRepository->findBy(
            [],
            ['createdAt' => 'desc'],
            3
        );

        $almo_intro_1 = $informationRepository->findOneBy([
            'title' => 'almo_intro_1'
        ]);

        $almo_intro_2 = $informationRepository->findOneBy([
            'title' => 'almo_intro_2'
        ]);

        // dd($almo_intro_1);

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('home/index.html.twig', [
            'documents' => $documents,
            'articles' => $articles,
            'webinars' => $webinars,
            'almo_intro_1' => $almo_intro_1,
            'almo_intro_2' => $almo_intro_2,
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
    }

    /**
     * @Route("/experiment", name="experiment")
     */
    public function experiment(RessourceRepository $ressourceRepository, WebinarRepository $webinarRepository, InformationRepository $informationRepository): Response
    {
        $almo_experiment = $informationRepository->findOneBy([
            'title' => 'almo_experiment'
        ]);

        return $this->render('home/experiment.html.twig', [
            'experiment' => $almo_experiment,
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
