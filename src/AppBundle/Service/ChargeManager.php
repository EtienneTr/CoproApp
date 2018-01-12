<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 08/12/2017
 * Time: 15:08
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use UserBundle\Service\UserService;
use AppBundle\Service\ChargePayementManager;

class ChargeManager extends CoproService
{

    private $userManager;
    private $payementManager;

    public function __construct(EntityManager $entityManager, UserService $userManager, ChargePayementManager $payementManager)
    {
        $this->userManager = $userManager;
        $this->payementManager = $payementManager;

        parent::__construct($entityManager,"AppBundle:Charge");
    }

    function getCharges()
    {
        return $this->findAll();
    }

    function getChargeById($id)
    {
        return $this->findOne($id);
    }

    function postCharge($charge)
    {
        $this->checkChargeOwners($charge);
        $this->create($charge);
        $this->payementManager->createPayements($charge);
    }

    function getToPayFromDate($date)
    {
        $queryBuilder = $this->em->createQueryBuilder();

        $req = $queryBuilder->select(array('c'))
            ->from('AppBundle:Charge', 'c')
            ->where('c.dueOn < :date and c.paid != 1')
            ->setParameter('date', $date)
            ->getQuery();

        return $req->getResult();
    }

    function checkChargeOwners($charge)
    {
        $owners = $charge->getOwners();

        if(sizeof($owners) <= 0)
        {
            $users = $this->userManager->getAllUsers();

            foreach($users as $user)
            {
                $owners->add($user);
            }
        }
    }

    function getUserChargesToPay()
    {
        $userId = $this->userManager->getUser()->getId();

        $req = $this->em->createQuery('SELECT c, cp FROM AppBundle:Charge c LEFT JOIN c.payments cp WHERE cp.charge = c.id AND cp.owner = :userId AND cp.paid = 0 ORDER BY c.dueOn ASC')
            ->setParameters(array('userId' => $userId));

        return $req->getResult();
    }

    function setChargePaid($charge)
    {
        $charge->setPaid(true);
        $this->update($charge);
    }

    function getLastChargesForUser($userId)
    {
        $qb = $this->repo
            ->createQueryBuilder("c")
            ->leftJoin('c.owners', 'co')
            ->where('(co.id IS NULL')
            ->orWhere(':user MEMBER OF c.owners)')
            ->setParameter('user', $userId)
            ->orderBy("c.creationDate", "DESC")
            ->setMaxResults(5)
            ->getQuery();

        return $qb->getResult();
    }
}