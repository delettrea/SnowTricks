<?php

namespace App\Form;

use App\Entity\Tricks;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TricksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
            ->add('description')
            ->add('group', EntityType::class, array(
                'class' => 'App:Groups',
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => false,
            ))
            ->add('files', FileType::class, [
                'multiple' => true
            ]);;

        $builder->add('videos', CollectionType::class, array(
            'entry_type' => VideosType::class,
            'entry_options' => array('label' => false),
            'allow_add' => true,
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
