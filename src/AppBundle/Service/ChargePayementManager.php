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


    public function postPayement($payement)
    {
        $this->create($payement);
    }

    private function postWaitPayement($payement)
    {
        $this->em->persist($payement);
    }


    public function createPayements($charge)
    {
        $owners = $charge->getOwners();
        $amount = $charge->getAmount();
        $nbOwner = sizeof($owners);
        $equalAmount = $amount / $nbOwner;

        foreach ($owners as $user) {
            $payement = new ChargePayement();
            $payement->setCharge($charge);
            $payement->setPaid(false);
            $payement->setOwner($user);
            $payement->setAmount($equalAmount);
            $this->postWaitPayement($payement);
        }

        $this->em->flush();
    }

    public function getByUserAndCharge($userId, $chargeId)
    {
        return $this->repo->findOneBy(
            array(
                'charge' => $chargeId,
                'owner' => $userId,
                'paid' => false
            ));
    }

    public function setChargePaymentPaid(ChargePayement $chargePayement)
    {
        $chargePayement->setPaid(true);
        $chargePayement->setPaymentDate(new \DateTime("now"));
        $this->update($chargePayement);
    }

    function getLastUnpaidPaymentsForUser($userId)
    {
        $qb = $this->repo
            ->createQueryBuilder("p")
            ->where('p.owner = :user')
            ->andWhere('p.paid = 0')
            ->setParameter('user', $userId)
            ->setMaxResults(5)
            ->getQuery();

        return $qb->getResult();
    }

    function getTotalPaymentForUserByMonth($userId)
    {
//        $req = $this->em->createQuery('
//        SELECT
//            DATE_FORMAT(c.paymentDate,\'%m-%Y\')  AS MOIS,
//            SUM(c.amount)  AS amount
//        FROM  charge_payement c
//        WHERE c.owner_id LIKE :userId AND MONTH(c.paymentDate) IS NOT NULL
//        GROUP BY DATE_FORMAT(c.paymentDate,\'%m-%Y\')
//        ORDER BY DATE_FORMAT(c.paymentDate,\'%m-%Y\')  ASC;')
//            ->setParameters(array(
//                'userId' => $userId
//            ));
//
//        return $req->getResult();
    }
    function getPaidPaymentForUserByMonth($userId)
    {
        //$req = $this->em->createQuery('
//        SELECT
//            DATE_FORMAT(c.paymentDate,\'%m-%Y\')  AS MOIS,
//            SUM(c.amount)  AS amount
//        FROM  charge_payement c
//        WHERE c.owner_id LIKE :userId AND MONTH(c.paymentDate) IS NOT NULL AND c.paid
//        GROUP BY DATE_FORMAT(c.paymentDate,\'%m-%Y\')
//        ORDER BY DATE_FORMAT(c.paymentDate,\'%m-%Y\')  ASC;')
//            ->setParameters(array(
//                'userId' => $userId
//            ));
//
//        return $req->getResult();
    }
}