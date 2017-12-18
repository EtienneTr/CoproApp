<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 08/12/2017
 * Time: 15:08
 */

namespace AppBundle\Service;


use Doctrine\ORM\EntityManager;

class ChargeManager extends CoproService
{

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager,"AppBundle:Charge");
    }

    function getCharges()
    {
        return $this->findAll();
    }

    function getMessagesForCurrentUser()
    {

    }

    function postCharge($charge)
    {
        $this->create($charge);
    }

}