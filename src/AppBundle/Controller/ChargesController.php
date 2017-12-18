<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etienne
 * Date: 15/12/2017
 * Time: 09:11
 */

namespace AppBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use AppBundle\Entity\Charge;
use AppBundle\Service\ChargeManager;

class ChargesController extends Controller
{
    /**
     * @Route("/charge", name="charges_create")
     * @Method({"GET", "POST"})
     */
    public function addChargesAction(ChargeManager $manager, Request $request){
        $charge = new Charge();

        $form = $this->createFormBuilder($charge)
            ->add("title", TextType::class)
            ->add("dueOn", DateType::class)
            ->add("amount", MoneyType::class)
            ->add("save", SubmitType::class, array('label' => "Créer une charge" ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chargeForm = $form->getData();

            $charge->setCreationDate(new \DateTime('now'));

            $manager->postCharge($charge);

            return $this->redirectToRoute('charge_all');
        }

        return $this->render('AppBundle:charges:create_form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/charge/all", name="charge_all")
     * @Method({"GET"})
     */
    public function listChargeAction(ChargeManager $manager)
    {
        $charges = $manager->findAll();
        //var_dump($messages[0]);
        return $this->render('AppBundle:charges:charges.html.twig', array(
            'charges' => $charges
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

        return $this->redirectToRoute('charge_all');
    }

}