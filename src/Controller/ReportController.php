<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Report;
use App\Form\ReportFormType;
use App\Repository\UserRepository;
use App\Repository\ReportRepository;
use App\Repository\DisponibilityRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReportController extends AbstractController
{
    /**
     * @Route("/report", name="report")
     */
    public function index(): Response
    {
        return $this->render('report/index.html.twig', [
            'controller_name' => 'ReportController',
        ]);
    }


    /**
     * @Route("/report/view/{id}", name="report_view", requirements={"id"="\d+"})
     */
    public function viewReport(Request $request, disponibilityRepository $disonibilityRepository): Response
    {

        // dd($disonibilityRepository->findOneBy([
        //     'id' => $request->get('id')])->getReport()->getReportPatient());

        return $this->render('report/view.html.twig', [
            'report' => $disonibilityRepository->findOneBy([
                'id' => $request->get('id')])->getReport()->getReportPatient(),
        ]);
    }



    /**
     * @Route("/report/add", name="report_add")
     */
    public function addReport(UserInterface $user, UserRepository $userRepository, DisponibilityRepository $disponibilityRepository, Request $request): Response
    {


        // dd($request->get('patientId'));

        $report = new Report();
        $report->setCreatedBy($user);
        $report->setPatient($userRepository->findOneBy([
            'id' => $request->get('patientId')]));
        $report->setDisponibility($disponibilityRepository->findOneBy([
            'id' => $request->get('disponibilityId')]));
        $form = $this->createForm(ReportFormType::class, $report);
        // $form->add('createdBy', EntityType::class, [
        //     'class' => User::class,
        // ])
            // $form->add('patient', EntityType::class, [
            //     'class' => User::class,
            // ])
            $form->add('reportPatient', CKEditorType::class,[
                'label' => 'Compte-rendu pour le patient'])
            ->add('reportAlmo', CKEditorType::class,[
                'label' => 'Compte-rendu pour Almo'])
            
            ->add('Envoyer', SubmitType::class);

        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($report);
            $em->flush();

            $this->addFlash('success', 'Votre rapport a été ajouter avec success !');

            return $this->redirectToRoute('user_reservation_list');
        }

        return $this->render('report/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }




    /**
     * @Route("/report/update/{id}", name="report_update", requirements={"id"="\d+"})
     */
    public function updateReport(disponibilityRepository $disonibilityRepository, Request $request): Response
    {

        $report = $disonibilityRepository->findOneBy([
            'id' => $request->get('id')])->getReport();
        $form = $this->createForm(ReportFormType::class, $report);
        $form->add('Envoyer', SubmitType::class);

        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($report);
            $em->flush();

            $this->addFlash('success', 'Votre rapport a été modifié avec success !');

            return $this->redirectToRoute('user_reservation_list');
        }

        return $this->render('report/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
