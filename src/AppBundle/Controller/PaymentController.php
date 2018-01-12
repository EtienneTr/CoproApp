<?php

namespace AppBundle\Controller;

use AppBundle\Service\BankPaymentManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use AppBundle\Entity\BankPayment;
use AppBundle\Form\BankPaymentType;

class PaymentController extends Controller
{
    /**
     * @Route("/payment/bank", name="payment_bank_create")
     * @Method({"GET", "POST"})
     */
    public function addBankPaymentAction(BankPaymentManager $manager, Request $request){
        $bankPayment = new BankPayment();

        $form = $this->createForm(BankPaymentType::class, $bankPayment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            #catch error from service (amount sum)
            try {
                $manager->postPayment($bankPayment);
                return $this->redirectToRoute('charge_user');
            }
            catch (\Exception $e)
            {
                #invalid charge
                if($e instanceof \InvalidArgumentException)
                {
                    $form->get('charge')->addError(new FormError($e->getMessage()));
                }
                #invalid amount
                if($e instanceof \UnexpectedValueException)
                {
                    $form->get('amount')->addError(new FormError($e->getMessage()));
                }
            }
        }

        return $this->render('AppBundle:payment:bank_create_form.html.twig', array(
            'form' => $form->createView()
        ));
    }
}