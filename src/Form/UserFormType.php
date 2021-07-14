<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Types\ArrayType;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class)
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'ROLE_USER' => 'ROLE_USER',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN'
                ],
            'expanded'  => true,
            'multiple' => true,
            'label' => 'Roles'
            ])
            // ->add('isVerified', BooleanType::class)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('address', TextType::class)
            ->add('city', TextType::class)
            ->add('zipcode', TextType::class)
            ->add('phone', TextType::class)
            ->add('hospital', ChoiceType::class, [
                'choices' => [
                    'Hopital 1' => 'Hopital 1',
                    'Hopital 2' => 'Hopital 2',
                    'Hopital 3' => 'Hopital 3'
                ],
            'expanded'  => true,
            'multiple' => true,
            'label' => 'Hopital'
            ])
            ->add('doctor', ChoiceType::class, [
                'choices' => [
                    'Doctor 1' => 'Doctor 1',
                    'Doctor 2' => 'Doctor 2',
                    'Doctor 3' => 'Doctor 3'
                ],
            'expanded'  => true,
            'multiple' => true,
            'label' => 'Doctor'
            ])
            ->add('Changer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
