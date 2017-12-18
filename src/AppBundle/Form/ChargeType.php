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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ChargeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", TextType::class)
            ->add("dueOn", DateType::class)
            ->add("amount", MoneyType::class)
            ->add('bill', FileType::class, array('label' => 'Facture (PDF file)'))
            ->add("save", SubmitType::class, array('label' => "Créer une charge" ))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array('data_class' => Charge::class)
        );
    }
}