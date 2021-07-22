<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserFormType;
use App\Security\EmailVerifier;
use App\Repository\UserRepository;
use App\Form\Admin\AdminUserDocFormType;
use App\Form\Admin\AdminUserProFormType;
use App\Form\Admin\AdminUserAdminFormType;
use App\Form\Admin\AdminUserPatientFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


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
    public function addUser(): Response
    {
        return $this->render('admin/user/add.html.twig', []);
    }


    /**
     * @Route("/admin/user/add_patient", name="admin_user_add_patient")
     */
    public function addUserPatient(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(AdminUserPatientFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setRoles(['ROLE_PATIENT']);
            $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
            $user->setHospital($form->get('hospital')->getData());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le nouveau patient a été enregistré avec succes dans la base de données');

            return $this->redirectToRoute('admin_user_add');
        }

        return $this->render('admin/user/add_patient.html.twig', [
            'form' => $form->createView(),
        ]);
    }




    /**
     * @Route("/admin/user/add_doc", name="admin_user_add_doc")
     */
    public function addUserDoc(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(AdminUserDocFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setRoles(['ROLE_DOC']);
            $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
            $user->setHospital($form->get('hospital')->getData());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le nouveau médecin a été enregistré avec succes dans la base de données');

            return $this->redirectToRoute('admin_user_add');
        }

        return $this->render('admin/user/add_doc.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/user/add_pro", name="admin_user_add_pro")
     */
    public function addUserPro(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(AdminUserProFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setRoles(['ROLE_PRO']);
            $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le nouveau professionel a été enregistré avec succes dans la base de données');

            return $this->redirectToRoute('admin_user_add');
        }

        return $this->render('admin/user/add_pro.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/admin/user/add_admin", name="admin_user_add_admin")
     */
    public function addUserAdmin(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(AdminUserAdminFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le nouveau administrateur a été enregistré avec succes dans la base de données');

            return $this->redirectToRoute('admin_user_add');
        }

        return $this->render('admin/user/add_pro.html.twig', [
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
