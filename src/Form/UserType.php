<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => "Nom d'utilisateur *",
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez renseigner un nom d'utilisateur."
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email *',
                'constraints' => [
                    new Email([
                        'message' => "Veuillez renseigner une adresse email valide."
                    ]),
                    new NotBlank([
                        'message' => "Veuillez renseigner une adresse email."
                    ])
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe *',
                    'constraints' => [
                       new NotBlank([
                           'message' => 'Veuillez renseigner un mot de passe.'
                       ])
                    ]
                ])
            ->add('avatar', FileType::class, [
                'label' => 'Votre avatar',
                'required'=> false
                    ])
                ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }


}
