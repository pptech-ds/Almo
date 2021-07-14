<?php

namespace App\Form;

use App\Entity\Ressource;
use App\Entity\RessourceCategory;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RessourceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            /*->add('slug')*/
            // ->add('content', TextareaType::class)
            ->add('content', CKEditorType::class, [
                "label" => "Contenu",
            ])
            ->add('image', TextType::class)
            /*->add('createdAt')*/
            /*->add('active')*/
            /*->add('user')*/
            ->add('ressourceCategory', EntityType::class, [
                'class' => RessourceCategory::class
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ressource::class,
        ]);
    }
}
