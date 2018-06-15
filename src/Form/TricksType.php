<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TricksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'Nom de la figure',
            'constraints' => [
                new NotBlank([
                    "message" => "Veuillez renseigner au moins le nom de la figure."
                ])
            ]
        ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la figure',
                'constraints' => [
                    new NotBlank([
                        "message" => "Veuillez renseigner au moins quelques mots sur la figure."
                    ])
                ]
            ])
            ->add('group', EntityType::class, array(
                'class' => 'App:Groups',
                'choice_label' => 'name',
                'label' => 'Groupe de la figure',
                'constraints' => [
                    new NotBlank([
                        'message' => "Votre figure doit faire partie d'une catégorie."
                    ])
                ]
            ));

        $builder->add('videos', CollectionType::class, array(
            'entry_type' => VideosType::class,
            'entry_options' => array('label' => false),
            'allow_add' => true,
            'label' => 'Vidéos',
            'required'=> false,
        ));

        $builder->add('illustrations', CollectionType::class, array(
            'entry_type' => IllustrationsType::class,
            'entry_options' => array('label' => false),
            'allow_add' => true,
            'label' => 'Images',
            'required'=> false
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => Tricks::class,
        ]);
    }
}
