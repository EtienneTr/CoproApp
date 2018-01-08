<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Entity\BankPayment;
use AppBundle\Service\FileUploader;
use AppBundle\Service\ChargeManager;
use AppBundle\Form\BankPaymentType;

class PaymentController extends Controller
{
    /**
     * @Route("/payment/bank", name="payment_bank_create")
     * @Method({"GET", "POST"})
     */
    public function addBankPaymentAction(ChargeManager $manager, FileUploader $fileUploader, Request $request){
        $bankPayment = new BankPayment();

        $form = $this->createForm(BankPaymentType::class, $bankPayment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $charge->setCreationDate(new \DateTime('now'));

            $bill = $form->get('bill')->getData();//$charge->getBill();
            
            if($bill)
            {
                $billName = $fileUploader->uploadFile($bill);
                $charge->setBill($billName);
            }
            $charge->setPaid(false);

            $manager->postCharge($charge);

            return $this->redirectToRoute('charge_user');
        }

        return $this->render('AppBundle:payment:bank_create_form.html.twig', array(
            'form' => $form->createView()
        ));
    }
}