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



    /**
     * @Route("/test_hospital", name="test_hospital")
     */
    public function testHospital(HospitalRepository $hospitalRepository): Response
    {
        // dd($hospitalRepository->findAll());

        $doctors = [];

        foreach($hospitalRepository->findAll() as $hospital) {
            // dd($hospital->getUser());
            foreach($hospital->getUser() as $user){
                // dd($user->getRoles()[0]);
                if($user->getRoles()[0] === 'ROLE_DOC'){
                    $doctors[$user->getEmail()] = $user->getEmail();
                }
            }
        }

        dd($doctors);
    }

}
