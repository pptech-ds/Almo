<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Hospital;
use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Types\ArrayType;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\FormEvent;
use App\Repository\HospitalRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Nouveau mot de passe',
                ],
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'label' => 'Répétez le mot de passe',
                ],
                'invalid_message' => 'The password fields must match.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('address', TextType::class)
            ->add('city', TextType::class)
            ->add('zipcode', TextType::class)
            ->add('phone', TextType::class)
            
            
            // ->add('hospital', EntityType::class, [
            //     'compound' => true,
            //     'class' => 'App\Entity\Hospital',
            //     'placeholder' => 'selectionner l\'hopital',
            //     'mapped' => false,
            //     // 'require' => false,
            //     // 'expanded'  => true,
            //     // 'multiple' => true,
            //     'label' => 'Hopital'
            // ])


            ->add('hospital', EntityType::class, [
                'mapped' => false,
                'class' => Hospital::class,
                'choice_label' => 'name',
                'placeholder' => 'Hospital',
                'label' => 'Hospital',
                'required' => false
            ])

            ->add('doctor', ChoiceType::class, [
                'placeholder' => 'choisir un medecin',
                'required' => false
            ])
            
            ->add('Envoyer', SubmitType::class);


            $formModifier = function (FormInterface $form, Hospital $hospital = null) {
                $doctors = null === $hospital ? [] : $hospital->getUser();
                
                
        
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
