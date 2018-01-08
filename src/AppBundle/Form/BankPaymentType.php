<?php
namespace AppBundle\Form;

use AppBundle\Entity\BankPayment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use AppBundle\Enum\BankPaymentTypeEnum;

class BankPaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("user", EntityType::class, array(
                'attr' => ['class' => 'form-control'],
                'class' => 'UserBundle:User',
                'choice_label' => 'username',
            ))
            ->add("charge", EntityType::class, array(
                'attr' => ['class' => 'form-control'],
                'class' => 'AppBundle:Charge',
                'choice_label' => 'title',
            ))
            ->add("amount", NumberType::class, array(
                'attr' => ['class' => 'form-control']
            ))
            ->add("paymentDate", DateType::class, array(
                'attr' => ['class' => 'form-control'],
                'widget' => 'single_text',
                'data' => new \DateTime('now'),
                'html5' => true))
            ->add("paymentType", ChoiceType::class, array(
                'required' => true,
                'choices' => BankPaymentTypeEnum::getAvailableTypes(),
                'choice_label' => function($choice) {
                    return BankPaymentTypeEnum::getTypeName($choice);
                },
            ))
            ->add('attachments', CollectionType::class, array(
                'entry_type' => MultiFileType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
            ))
            ->add("save", SubmitType::class, array(
                'attr' => ['class' => 'btn btn-success'],
                'label' => "Enregistrer"
            ))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array('data_class' => BankPayment::class)
        );
    }
}