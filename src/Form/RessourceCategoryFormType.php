<?php

namespace App\Form;

use App\Entity\RessourceCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RessourceCategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                "label" => "Nom de la catégorie",
            ])
            ->add('description', TextareaType::class,[
                'label' => 'Description de la catégorie'
            ])
            ->add('image', TextType::class, [
                "label" => "Image",
            ])
            /*->add('slug')*/
            ->add('parent')
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RessourceCategory::class,
        ]);
    }
}
