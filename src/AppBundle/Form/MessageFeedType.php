<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 18/12/2017
 * Time: 10:32
 */

namespace AppBundle\Form;

use AppBundle\Entity\MessageFeed;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MessageFeedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("body", TextareaType::class, array(
                'attr' => ['class' => 'form-control']
            ))
            ->add("save", SubmitType::class, array(
                'label' => "Répondre au message",
                'attr' => ['class' => 'btn btn-success'],
            ))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array('data_class' => MessageFeed::class)
        );
    }
}