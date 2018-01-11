<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 08/12/2017
 * Time: 15:08
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class ContractManager extends CoproService
{

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager,"AppBundle:Contract");
    }

    function postContract($contract)
    {
        $this->create($contract);
    }

    function getUserContracts($userId)
    {
        return $this->repo->findBy(
            array(
                'user' => $userId
            ), array('startDate' => 'asc')
        );
    }

}