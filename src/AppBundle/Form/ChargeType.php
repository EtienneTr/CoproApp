<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 18/12/2017
 * Time: 10:32
 */

namespace AppBundle\Form;

use AppBundle\Entity\Charge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ChargeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", TextType::class, array(
                'attr' => ['class' => 'form-control'],
            ))
            ->add("dueOn", DateType::class, array(
                'attr' => ['class' => 'form-control'],
                'widget' => 'single_text',
                'html5' => true))
            ->add("amount", NumberType::class, array(
                'attr' => ['class' => 'form-control']
            ))
            ->add("owners", EntityType::class, array(
                'attr' => ['class' => 'form-control selectpicker'],
                'label' => 'Propriétaires',
                'class' => 'UserBundle:User',
                'choice_label' => 'username',
                'required' => false,
                'multiple' => true
            ))
            ->add("contract", EntityType::class, array(
                'attr' => ['class' => 'form-control selectpicker'],
                'class' => 'AppBundle:Contract',
                'choice_label' => 'name',
                'required' => false,
            ))
            ->add('bill', FileType::class, array(
                'label' => 'Facture (PDF file)', 'required' => false
            ))
            ->add("save", SubmitType::class, array(
                'attr' => ['class' => 'btn btn-success'],
                'label' => "Créer une charge"
            ))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array('data_class' => Charge::class)
        );
    }
}