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
    public function specialityIndex(SpecialityRepository $specialityRepository): Response
    {
        return $this->render('admin/speciality/index.html.twig', [
            'specialities' => $specialityRepository->findAll(),
        ]);
    }

}
