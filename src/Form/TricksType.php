<?php

namespace App\Form;

use App\Entity\Tricks;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ->add('description', TextType::class, [
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
                'expanded' => true,
                'multiple' => false,
                'label' => 'Groupe de la figure',
                'constraints' => [
                    new NotBlank([
                        'message' => "Votre figure doit faire partie d'une catégorie."
                    ])
                ]
            ))
            ->add('files', FileType::class, [
                'multiple' => true,
                'label' => 'Photos et images de la figure',
                'required'=> false
            ]);;

        $builder->add('videos', CollectionType::class, array(
            'entry_type' => VideosType::class,
            'entry_options' => array('label' => false),
            'allow_add' => true,
            'label' => 'Vidéos de la figure',
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
