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

class BankPaymentManager extends CoproService
{
    private $fileUploader;

    public function __construct(EntityManager $entityManager, FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;

        parent::__construct($entityManager,"AppBundle:BankPayment");
    }


    function postPayment($payment)
    {
        $charge = $payment->getCharge();
        if($this->checkPaymentCharge($charge, $payment->getUser()) == null)
        {
            throw new InvalidArgumentException("L'utilisateur n'a pas de paiement pour cette charge");
        }
        #save attachment
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

        $this->create($payment);
    }

    function checkPaymentCharge($charge, $user)
    {
        $userId = $user->getId();

        $req = $this->em->createQuery('SELECT c, cp FROM AppBundle:Charge c 
                                      LEFT JOIN c.payments cp 
                                      WHERE cp.charge = c.id AND c.id = :cid AND cp.owner = :userId AND cp.paid = 0 ORDER BY c.dueOn ASC')
            ->setParameters(array('userId' => $userId, 'cid' => $charge->getId()));

        return $req->getResult();
    }

    function getProjects()
    {
        return $this->findAll();
    }

}