<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use UserBundle\Service\UserService;
use AppBundle\Entity\ChargePayement;

class ChargePayementManager extends CoproService
{

    private $userManager;

    public function __construct(EntityManager $entityManager, UserService $userManager)
    {
        $this->userManager = $userManager;

        parent::__construct($entityManager, "AppBundle:ChargePayement");
    }


    function postPayement($payement)
    {
        $this->create($payement);
    }

    function postWaitPayement($payement)
    {
        $this->em->persist($payement);
    }


    function createPayements($charge)
    {
        $owners = $charge->getOwners();
        $amount = $charge->getAmount();
        $nbOwner = sizeof($owners);
        $equalAmount = $amount / $nbOwner;

        foreach($owners as $user)
        {
            $payement = new ChargePayement();
            $payement->setCharge($charge);
            $payement->setPaid(false);
            $payement->setOwner($user);
            $payement->setAmount($equalAmount);
            $this->postWaitPayement($payement);
        }

        $this->em->flush();
    }

}