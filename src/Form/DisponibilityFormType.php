<?php

namespace App\Form;

use App\Entity\Disponibility;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DisponibilityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('content', TextareaType::class, ['label' => 'Contenu'])
            ->add('startTime', DateTimeType::class, ['label' => 'Heure du début'])
            ->add('endTime', DateTimeType::class, ['label' => 'Heure de fin'])
            ->add('isVisio', CheckboxType ::class, ['label' => 'En Visio ?'])
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
