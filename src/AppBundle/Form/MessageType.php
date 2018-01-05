<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 18/12/2017
 * Time: 10:32
 */

namespace AppBundle\Form;

use AppBundle\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("body", TextareaType::class, array(
                'attr' => ['class' => 'form-control']
            ))
            ->add("receiver", EntityType::class, array(
                'attr' => ['class' => 'form-control selectpicker'],
                'class' => 'UserBundle:User',
                'choice_label' => 'username',
                'required' => false,
                'multiple' => true))
            ->add("save", SubmitType::class, array(
                'attr' => ['class' => 'btn btn-success'],
                'label' => "Créer un message"
            ))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array('data_class' => Message::class)
        );
    }
}