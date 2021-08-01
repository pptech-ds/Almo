<?php

namespace App\Controller\Admin;

use App\Entity\Ressource;
use App\Form\RessourceFormType;
use App\Entity\RessourceCategory;
use App\Repository\HospitalRepository;
use App\Form\RessourceCategoryFormType;
use App\Repository\RessourceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RessourceCategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/admin", name="admin_")
*/
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(RessourceRepository $ressourceRepository): Response
    {
        $ressources = $ressourceRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'ressources' => $ressources,
        ]);
    }

}
