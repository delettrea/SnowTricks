<?php

namespace App\Form;

use App\Entity\Tricks;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditTrickType extends AbstractType
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
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class
        ]);
    }
}

