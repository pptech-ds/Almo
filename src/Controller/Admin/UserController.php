<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Hospital;
use App\Security\EmailVerifier;
use App\Repository\UserRepository;
use App\Form\Admin\AdminUserFormType;
use App\Form\Admin\AdminUserPatientFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
     * @Route("/admin/user_by_role/{role}", name="admin_user_by_role_index")
     */
    public function userIndexByRole(UserRepository $userRepository, Request $request): Response
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
    public function userAdd(): Response
    {
        
        return $this->render('admin/user/add.html.twig', []);
    }



    /**
     * @Route("/admin/user/add/{role}", name="admin_user_add_by_role")
     */
    public function userAddByRole(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $role = $request->get('role');


        $user = new User();
        
        if($role == 'ROLE_PATIENT'){
            $form = $this->createForm(AdminUserPatientFormType::class, $user);
            
        }
        if($role == 'ROLE_DOC'){
            $form = $this->createForm(AdminUserFormType::class, $user);
            $form->add('hospital', EntityType::class, [
                'mapped' => false,
                'class' => Hospital::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir un hopital',
                'label' => 'Hopital',
                'required' => false
            ])
            
                ->add('details', TextareaType::class,['label' => 'Informations complémentaires (500 caractères max)'])
            
                ->add('Enregistrer', SubmitType::class);
        }
        if(($role == 'ROLE_PRO') || ($role == 'ROLE_ADMIN')){
            $form = $this->createForm(AdminUserFormType::class, $user);
            $form->add('details', TextareaType::class,['label' => 'Informations complémentaires (500 caractères max)'])
            
                ->add('Enregistrer', SubmitType::class);
        }
        
        // $form->add('Enregistrer', SubmitType::class);
        $form->handleRequest($request);

        

        if ($form->isSubmitted() && $form->isValid()) {

            if($role == 'ROLE_PATIENT'){
                $user->setRoles(['ROLE_PATIENT']);
                $user->setHospital($form->get('hospital')->getData());
            }
            if($role == 'ROLE_DOC'){
                $user->setRoles(['ROLE_DOC']);
                $user->setHospital($form->get('hospital')->getData());
            }
            if($role == 'ROLE_PRO'){
                $user->setRoles(['ROLE_PRO']);
            }
            if($role == 'ROLE_ADMIN'){
                $user->setRoles(['ROLE_ADMIN']);
            }

            $user->setIsVerified(1);
            $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le nouveau patient a été enregistré avec succes dans la base de données');

            return $this->redirectToRoute('admin_user_add');
        }

        return $this->render('admin/user/add_by_role.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    

    /**
     * @Route("/admin/user/update/{id}", name="admin_user_update", requirements={"id"="\d+"})
     */
    public function userUpdate(User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        // $role = $request->get('role');
        $role = $user->getRoles()[0];
        
        // $user = new User();
        if($role == 'ROLE_PATIENT'){
            $form = $this->createForm(AdminUserPatientFormType::class, $user);
            
        }
        if($role == 'ROLE_DOC'){
            $form = $this->createForm(AdminUserFormType::class, $user);
            $form->add('hospital', EntityType::class, [
                'mapped' => false,
                'class' => Hospital::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir un hopital',
                'label' => 'Hopital',
                'required' => false
            ])
            
                ->add('details', TextareaType::class,['label' => 'Informations complémentaires (500 caractères max)'])
            
                ->add('Enregistrer', SubmitType::class);
        }
        if(($role == 'ROLE_PRO') || ($role == 'ROLE_ADMIN')){
            $form = $this->createForm(AdminUserFormType::class, $user);
            $form->add('details', TextareaType::class,['label' => 'Informations complémentaires (500 caractères max)'])
            
                ->add('Enregistrer', SubmitType::class);
        }
        
        // $form->add('Enregistrer', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($role == 'ROLE_PATIENT'){
                $user->setRoles(['ROLE_PATIENT']);
                $user->setHospital($form->get('hospital')->getData());
            }
            if($role == 'ROLE_DOC'){
                $user->setRoles(['ROLE_DOC']);
                $user->setHospital($form->get('hospital')->getData());
            }
            if($role == 'ROLE_PRO'){
                $user->setRoles(['ROLE_PRO']);
            }
            if($role == 'ROLE_ADMIN'){
                $user->setRoles(['ROLE_ADMIN']);
            }
            $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur a été mis a jour avec succes dans la base de données');

            return $this->redirectToRoute('admin_home');
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

        return $this->redirectToRoute('admin_home');
    }

}
