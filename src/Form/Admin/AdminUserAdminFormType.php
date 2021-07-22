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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminUserAdminFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('details', TextareaType::class,['label' => 'Informations complémentaires (500 caractères max)'])
            
            ->add('Enregistrer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
