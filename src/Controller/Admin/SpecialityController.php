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
    public function listSpecialities(SpecialityRepository $specialityRepository): Response
    {
        return $this->render('admin/speciality/index.html.twig', [
            'specialities' => $specialityRepository->findAll(),
        ]);
    }


    /**
     * @Route("/admin/speciality/name/{slug}", name="admin_speciality_by_name")
     */
    public function disponibilitiesBySpeciality(SpecialityRepository $specialityRepository, Request $request): Response
    {
        
        // $speciality = $request->get('name');

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

        return $this->render('admin/speciality/appointment_by_name.html.twig', [
            'usersBySpeciality' => $arrayDispo,
        ]);
    }
}
