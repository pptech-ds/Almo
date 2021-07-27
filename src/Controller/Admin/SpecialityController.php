<?php

namespace App\Controller\Admin;

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
     * @Route("/admin/speciality", name="admin_speciality_index")
     */
    public function index(SpecialityRepository $specialityRepository): Response
    {
        
        $specialities = $specialityRepository->findAll();
        
   
        return $this->render('admin/speciality/index.html.twig', [
            'specialities' => $specialities,
        ]);
    }


    /**
     * @Route("/admin/speciality/{slug}", name="admin_speciality_view", methods={"GET"})
     */
    public function view(SpecialityRepository $specialityRepository, Request $request): Response
    {
        // dd($specialityRepository->findBy(['slug' => $request->get('slug')])[0]->getUsers());

        $usersBySpeciality = $specialityRepository->findBy(['slug' => $request->get('slug')])[0]->getUsers();

        $arrayDispo = [];

        for($j = 0 ; $j< count($usersBySpeciality); $j++){
            
            // for($i = 0; $i< count($usersBySpeciality[$j]->getDisponibilities()); $i++) {

            //     $arrayDispo[] = ($usersBySpeciality[$j]->getDisponibilities()[$i]);
            // }

            if(count($usersBySpeciality[$j]->getDisponibilities()) > 0){
                $arrayDispo[] = $usersBySpeciality[$j];
            }
        }

        // dd($arrayDispo);

        return $this->render('speciality/view.html.twig', [
            'usersBySpeciality' => $arrayDispo,
        ]);
    }
}
