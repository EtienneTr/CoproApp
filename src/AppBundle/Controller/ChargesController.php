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
use AppBundle\Service\ChargeManager;
use AppBundle\Service\FileUploader;
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

            $bill = $charge->getBill();
            
            if($bill)
            {
                $billName = $fileUploader->upload($bill);
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