<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 18/12/2017
 * Time: 10:32
 */

namespace AppBundle\Form;

use AppBundle\Entity\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultiFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("file", FileType::class, array(
                'attr' => ['class' => 'form-control']
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        /*$resolver->setDefaults(
            //array('data_class' => Up::class)
        );*/
    }
}