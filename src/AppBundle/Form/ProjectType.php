<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 18/12/2017
 * Time: 10:32
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use AppBundle\Entity\Project;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class)
            ->add("description", TextType::class)
            ->add("users", EntityType::class, array(
                'label' => 'Propriétaires',
                'class' => 'UserBundle:User',
                'choice_label' => 'username',
                'required' => false,
                'multiple' => true ))
            #survey
            ->add('survey', CollectionType::class, array(
                'entry_type' => SurveyType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
            ))
            ->add('attachment',CollectionType::class, array(
                'entry_type' => MultiFileType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
            ))
            ->add("save", SubmitType::class, array('label' => "Créer un projet" ))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array('data_class' => Project::class)
        );
    }
}