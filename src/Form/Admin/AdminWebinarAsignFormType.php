<?php

namespace App\Form\Admin;

use App\Entity\User;
use App\Entity\Webinar;
use App\Entity\WebinarCategory;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AdminWebinarAsignFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', EntityType::class, [
                'class' => Webinar::class,
                'label' => 'Webinar'
            ])
            ->add('reservedBy', EntityType::class, [
                'class' => User::class,
                'label' => 'Patient'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Webinar::class,
        ]);
    }
}
