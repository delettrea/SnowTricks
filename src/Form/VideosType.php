<?php
/**
 * Created by PhpStorm.
 * User: aline
 * Date: 23/04/18
 * Time: 15:37
 */

namespace App\Form;

use App\Entity\Videos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class VideosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => false,
            'required' => false,
            'constraints' => new Regex([
                'pattern' => "#<iframe(.+)</iframe>#",
                'message' => 'Votre video doit être entre deux balises <iframe>'
            ])
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Videos::class,
        ));
    }
}