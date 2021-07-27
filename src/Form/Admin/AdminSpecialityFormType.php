<?php

namespace App\Form\Admin;

use App\Entity\Speciality;
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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminSpecialityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('speciality', EntityType::class, [
                'mapped' => false,
                'class' => Speciality::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir une spécialité',
                'label' => 'Nom',
                'required' => false
            ])

            ->add('praticien', ChoiceType::class, [
                'placeholder' => 'Choisir un praticien',
                'required' => false,
                'label' => 'Le praticien'
            ])

            
            ->add('Afficher', SubmitType::class);


            $formModifier = function (FormInterface $form, Speciality $speciality = null) {
                $usersInSpeciality = null === $speciality ? [] : $speciality->getUsers();

                $praticiens = [];
                
                foreach($usersInSpeciality as $userInSpeciality){
                    if($userInSpeciality->getRoles()[0] === 'ROLE_PRO'){
                        $praticiens[] = $userInSpeciality;
                    }
                };
                

        
                $form->add('praticien', EntityType::class, [
                    'class' => User::class,
                    'choices' => $praticiens,
                    'required' => false,
                    'choice_label' => 'email',
                    'placeholder' => 'choisir un praticien',
                    'attr' => ['class' => 'custom-select'],
                    'label' => 'praticien'
                ]);
            };


            $builder->get('speciality')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $speciality = $event->getForm()->getData();
                    $formModifier($event->getForm()->getParent(), $speciality);
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
