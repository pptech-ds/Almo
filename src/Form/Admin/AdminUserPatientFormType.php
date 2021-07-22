<?php

namespace App\Form\Admin;

use App\Entity\User;
use App\Entity\Hospital;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class AdminUserPatientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('roles', ChoiceType::class, [
            //     'choices' => [
            //         'ROLE_PATIENT' => 'ROLE_PATIENT'
            //     ],
            // 'expanded'  => false,
            // 'multiple' => true,
            // 'label' => 'Type d\'utilisateur'
            // ])
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le mot de passe doit être renseigner',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit etre d\'au moins {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Mot de passe',
                ],
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'label' => 'Répétez le mot de passe',
                ],
                'invalid_message' => 'Les mots de passe de correspondent pas',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
            ->add('civility', ChoiceType::class, [
                'choices' => [
                    'Mr' => 'Mr',
                    'Mme' => 'Mme',
                    'Dr' => 'Dr'
                ],
            'label' => 'Civilité'
            ])
            ->add('lastname', TextType::class,['label' => 'Nom'])
            ->add('firstname', TextType::class,['label' => 'Prénom'])
            ->add('address', TextType::class,['label' => 'Adresse'])
            ->add('city', TextType::class,['label' => 'Ville'])
            ->add('zipcode', TextType::class,['label' => 'Code postal'])
            ->add('phone', TextType::class,['label' => 'Téléphone'])
            ->add('hospital', EntityType::class, [
                'mapped' => false,
                'class' => Hospital::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir un hopital',
                'label' => 'Hopital',
                'required' => false
            ])

            ->add('doctor', ChoiceType::class, [
                'placeholder' => 'Choisir un médecin',
                'required' => false,
                'label' => 'Médecin Traitant'
            ])
            
            ->add('Enregistrer', SubmitType::class);


            $formModifier = function (FormInterface $form, Hospital $hospital = null) {
                $usersInHospital = null === $hospital ? [] : $hospital->getUser();

                $doctors = [];
                
                foreach($usersInHospital as $userInHospital){
                    if($userInHospital->getRoles()[0] === 'ROLE_DOC'){
                        $doctors[] = $userInHospital;
                    }
                };
                

        
                $form->add('doctor', EntityType::class, [
                    'class' => User::class,
                    'choices' => $doctors,
                    'required' => false,
                    'choice_label' => 'email',
                    'placeholder' => 'choisir un medecin',
                    'attr' => ['class' => 'custom-select'],
                    'label' => 'medecin'
                ]);
            };


            $builder->get('hospital')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $hospital = $event->getForm()->getData();
                    $formModifier($event->getForm()->getParent(), $hospital);
                }
            );


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
