<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 18/12/2017
 * Time: 10:32
 */

namespace AppBundle\Form;

use AppBundle\Entity\Contract;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("startDate", DateType::class, array(
                'attr' => ['class' => 'form-control'],
                'widget' => 'single_text',
                'html5' => true
            ))
            ->add("endDate", DateType::class, array(
                'attr' => ['class' => 'form-control'],
                'widget' => 'single_text',
                'html5' => true
            ))
            ->add("user", EntityType::class, array(
                'attr' => ['class' => 'form-control selectpicker'],
                'class' => 'UserBundle:User',
                'choice_label' => 'username'
            ));
            //if(!$options['update']) {
                $builder->add('attachment', FileType::class, array(
                    'label' => 'Facture (PDF file)',
                    'required' => false,
                    'data' => null
                ));
            //}
            $builder->add("save", SubmitType::class, array(
                'attr' => ['class' => 'btn btn-success'],
                'label' => "Enregistrer un contrat"
            ))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Contract::class,
                'update' => false
            )
        );
    }
}