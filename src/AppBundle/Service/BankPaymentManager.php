<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 08/12/2017
 * Time: 15:08
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Psr\Log\InvalidArgumentException;
use AppBundle\Service\ChargePayementManager;

class BankPaymentManager extends CoproService
{
    private $fileUploader;
    private $chargeManager;

    public function __construct(EntityManager $entityManager, ChargePayementManager $chargePayementManager, FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
        $this->chargePayementManager = $chargePayementManager;

        parent::__construct($entityManager,"AppBundle:BankPayment");
    }


    public function postPayment($payment)
    {
        $charge = $payment->getCharge();
        $chargePayment = $this->chargePayementManager->getByUserAndCharge($payment->getUser()->getId(), $charge);
        if($chargePayment == null)
        {
            throw new InvalidArgumentException("L'utilisateur n'a pas de paiement pour cette charge.");
        }

        #check amount limit
        $paymentAmount = $payment->getAmount();
        $userChargeAmount = $chargePayment->getAmount();
        if($paymentAmount > $userChargeAmount)
        {
            throw new InvalidArgumentException("Le montant du paiement est trop élevé, la charge étant de ".$userChargeAmount." €.");
        }
        else
        {
            #Chek sum old payments + current
           $this->checkOlderPayments($payment, $charge, $userChargeAmount);
        }

        #save attachment
        $this->saveAttachments($payment);
        $this->create($payment);
    }

    public function getByChargeId($chargeId)
    {
        return $this->repo->findBy(
            array(
                'charge' => $chargeId
            ),
            array( #order
                'paymentDate' => 'DESC'
            )
        );
    }

    public function getByUserAndCharge($userId, $chargeId)
    {
        return $this->repo->findBy(
            array(
                'charge' => $chargeId,
                'user' => $userId
            ));
    }

    private function saveAttachments($payment)
    {
        $files = $payment->getAttachments();
        $newFiles = [];
        foreach ($files as $file)
        {
            #multi-files case
            if(is_array($file)) {
                $file = array_shift($file);
            }
            $fileName = $this->fileUploader->uploadFile($file);
            array_push($newFiles, $fileName);
        }
        $payment->setAttachments($newFiles);
    }

    private function checkOlderPayments($payment, $charge, $userChargeAmount)
    {
        $paymentAmount = $payment->getAmount();

        $userPayments = $this->getByUserAndCharge($payment->getUser()->getId(), $charge->getId());
        $sumPayments = array_reduce(
            $userPayments, 
            function($sum, $item) { return $sum + $item->getAmount();  }, 
            0
        );#default sum = 0;
        
        if($paymentAmount + $sumPayments > $userChargeAmount)
        {
            throw new InvalidArgumentException("Le montant du paiement est trop élevé, la charge restante étant de ".($userChargeAmount - $sumPayments)." €.");
        }

        #full paiement
        if($paymentAmount + $sumPayments == $userChargeAmount)
        {
            $this->chargePayementManager->setChargePaymentPaid($chargePayment);
        }
    }
}