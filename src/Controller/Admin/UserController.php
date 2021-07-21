<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Ressource;
use App\Form\UserFormType;
use App\Form\RessourceFormType;
use App\Security\EmailVerifier;
use App\Entity\RessourceCategory;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use App\Repository\HospitalRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use App\Form\RessourceCategoryFormType;
use App\Repository\RessourceRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RessourceCategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }
    
    
    /**
     * @Route("/admin/user", name="admin_user_index")
     */
    public function indexUser(UserRepository $userRepository): Response
    {
        // $user = $userRepository->findBy([
        //     'id' => 15
        // ]);
        // dd($user[0]->getHospital()->getName());

        $usersRender = [];

        foreach($userRepository->findAll() as $user){
            $usersRenderLocal['user'] = $user;

            if($userRepository->findBy(['id' => $user->getId()])[0]->getHospital() != null) {
                $usersRenderLocal['hospital'] = $userRepository->findBy(['id' => $user->getId()])[0]->getHospital()->getName();
            } else{
                $usersRenderLocal['hospital'] = '';
            }

            if($userRepository->findBy(['id' => $user->getId()])[0]->getUser() != null) {
                $usersRenderLocal['doctor'] = $userRepository->findBy(['id' => $user->getId()])[0]->getUser()->getEmail();
            } else{
                $usersRenderLocal['doctor'] = '';
            }

            $usersRender[] = $usersRenderLocal;
        }

        // dd($usersRender);


        return $this->render('admin/user/index.html.twig', [
            // 'users' => $userRepository->findAll(),
            'users' => $usersRender,
        ]);
    }



    /**
     * @Route("/admin/user_by_role/{role}", name="admin_user_by_role_index")
     */
    public function indexUserByRole(UserRepository $userRepository, Request $request): Response
    {
        // dd($userRepository->findByRole('ROLE_USER'));
        // dd($userRepository->findBy(['roles' => '[\"ROLE_ADMIN\"]']));

        $role = $request->get('role');

        // dd($role);

        return $this->render('admin/user/index_by_role.html.twig', [
            // 'users' => $userRepository->findAll(),
            'users' => $userRepository->findByRole($role),
        ]);
    }


    
    
    /**
     * @Route("/admin/user/add", name="admin_user_add")
     */
    public function addUser(Request $request, UserPasswordEncoderInterface $passwordEncoder, HospitalRepository $hospitalRepository): Response
    {

        // dd($hospitalRepository->findAll());

        $hostpitals = [];

        foreach ($hospitalRepository->findAll() as $hospital) {
            // // dd($hospital->getUser());
            // foreach($hospital->getUser() as $userInHospital){
            //     dd($userInHospital->getEmail());
            // }

            $hostpitals[$hospital->getName()] = $hospital;
        }

        // dd($hostpitals);

        $doctors = [];

        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        
        

        $form->handleRequest($request);

        

        if ($form->isSubmitted() && $form->isValid()) {

            // dd($form->get('hospital')->getData());

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            // $currentHospital = $form->get('hospital');
            $user->setHospital($form->get('hospital')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            

            $this->addFlash('success', 'Le nouvel utilisateur a été enregistré avec succes dans la base de données');

            return $this->redirectToRoute('admin_user_add');
        }

        return $this->render('admin/user/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    
    /**
     * @Route("/admin/user/update/{id}", name="admin_user_update", requirements={"id"="\d+"})
     */
    public function updateUser(User $user, Request $request): Response
    {
        $form = $this->createForm(UserFormType::class, $user);
        $form->add('roles', ChoiceType::class, [
                'choices' => [
                    'ROLE_USER' => 'ROLE_USER',
                    'ROLE_DOC' => 'ROLE_DOC',
                    'ROLE_PRO' => 'ROLE_PRO',
                    'ROLE_ADMIN' => 'ROLE_ADMIN'
                ],
                'expanded'  => true,
                'multiple' => true,
                'label' => 'Roles'
            ])
            // ->add('hospital', ChoiceType::class, [
            //     'choices' => [
            //         'Hopital 1' => 'Hopital 1',
            //         'Hopital 2' => 'Hopital 2',
            //         'Hopital 3' => 'Hopital 3'
            //     ],
            //     'expanded'  => true,
            //     'multiple' => true,
            //     'label' => 'Hopital'
            // ])
            // ->add('doctor', ChoiceType::class, [
            //     'choices' => [
            //         'Doctor 1' => 'Doctor 1',
            //         'Doctor 2' => 'Doctor 2',
            //         'Doctor 3' => 'Doctor 3'
            //     ],
            //     'expanded'  => true,
            //     'multiple' => true,
            //     'label' => 'Medecin'
            // ])
            ->add('Envoyer', SubmitType::class)
            ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre utilisateur a été modifié avec succes !');

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/admin/user/verify/{id}", name="admin_user_verify", requirements={"id"="\d+"})
     */
    public function verifyUser(User $user): Response
    {
        $user->setIsVerified( ($user->isVerified()) ?  false : true );

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new Response('true');
    }


    /**
     * @Route("/admin/user/delete/{id}", name="admin_user_delete", requirements={"id"="\d+"})
     */
    public function deleteUser(User $user): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'L\'utilisateur a été supprimé avec succes !');

        return $this->redirectToRoute('admin_user_index');
    }

}
