<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etienne
 * Date: 15/12/2017
 * Time: 09:11
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Entity\Charge;
use AppBundle\Service\FileUploader;
use AppBundle\Service\ChargeManager;
use AppBundle\Service\BankPaymentManager;
use AppBundle\Form\ChargeType;

class ChargesController extends Controller
{
    /**
     * @Route("/charge", name="charges_create")
     * @Method({"GET", "POST"})
     */
    public function addChargesAction(ChargeManager $manager, FileUploader $fileUploader, Request $request){
        $charge = new Charge();

        $form = $this->createForm(ChargeType::class, $charge);
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

        return $this->render('AppBundle:charges:create_form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/charge/delete/{id}", name="charge_delete_id")
     * @Method({"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteChargeAction(ChargeManager $manager, $id)
    {
        $charge = $manager->findOne($id);
        $manager->remove($charge);

        return $this->redirectToRoute('charge_user');
    }


    /**
     * @Route("/charge/all", name="charge_user")
     * @Method({"GET"})
     */
    public function userChargeAction(ChargeManager $manager)
    {
        $charges = $manager->getUserChargesToPay();
        return $this->render('AppBundle:charges:user_charges.html.twig', array(
            'charges' => $charges
        ));
    }

    /**
     * @Route("/charge/detail/{id}", name="charge_detail")
     * @Method({"GET"})
     */
    public function detailChargeAction(ChargeManager $chargeManager, BankPaymentManager $paymentManager, $id)
    {
        $charge = $chargeManager->getChargeById($id);
        $payments = $paymentManager->getByChargeId($id);

        if(!$charge)
        {
            throw $this->createNotFoundException('The charge does not exist');
        }

        return $this->render('AppBundle:charges:detail.html.twig', array(
            'charge' => $charge,
            'bankPayments' => $payments
        ));
    }

    /**
     * @Route("/admin/charge/all", name="admin_charge_all")
     * @Method({"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function listChargeAction(ChargeManager $manager)
    {
        $charges = $manager->findAll();
        return $this->render('AppBundle:charges:charges.html.twig', array(
            'charges' => $charges
        ));
    }

}