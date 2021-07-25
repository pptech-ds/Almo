<?php

namespace App\Form;

use App\Entity\Disponibility;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DisponibilityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('content', TextareaType::class)
            ->add('startTime', DateTimeType::class)
            ->add('endTime', DateTimeType::class)
            // ->add('createdBy', EntityType::class)
            // ->add('reservedBy', EntityType::class)
            ->add('Enregistrer', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Disponibility::class,
        ]);
    }
}
