<?php

namespace App\Controller;

use App\Entity\Speciality;
use App\Repository\SpecialityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SpecialityController extends AbstractController
{
    /**
     * @Route("/speciality", name="speciality_index")
     */
    public function index(Request $request, PaginatorInterface $paginator, SpecialityRepository $specialityRepository): Response
    {
        
        $specialities = $specialityRepository->findAll();
        // $specialitys = $specialityRepository->findBy([],['createdAt' => 'desc']);
        // $specialitys = $specialityRepository->findAllspecialitysByCategory('speciality');

        $specialities_paginated = $paginator->paginate(
            $specialities,
            $request->query->getInt('page', 1),
            9
        );
   
        return $this->render('speciality/index.html.twig', [
            'specialities' => $specialities_paginated,
        ]);
    }


    /**
     * @Route("/speciality/{slug}", name="speciality_view", methods={"GET"})
     */
    public function view(SpecialityRepository $specialityRepository, Request $request): Response
    {
        // dd($specialityRepository->findBy(['slug' => $request->get('slug')])[0]->getUsers());

        $usersBySpeciality = $specialityRepository->findBy(['slug' => $request->get('slug')])[0]->getUsers();

        $arrayDispo = [];

        for($j = 0 ; $j< count($usersBySpeciality); $j++){
            
            // for($i = 0; $i< count($usersBySpeciality[$j]->getAppointments()); $i++) {

            //     $arrayDispo[] = ($usersBySpeciality[$j]->getAppointments()[$i]);
            // }

            if(count($usersBySpeciality[$j]->getAppointments()) > 0){
                $arrayDispo[] = $usersBySpeciality[$j];
            }
        }

        // dd($arrayDispo[0]);

        return $this->render('speciality/view.html.twig', [
            'usersBySpeciality' => $arrayDispo,
        ]);
    }
}
