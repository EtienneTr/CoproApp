<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 08/12/2017
 * Time: 15:08
 */

namespace AppBundle\Service;


use Doctrine\ORM\EntityManager;

class MessageManager extends CoproService
{

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager,"AppBundle:Message");
    }

    function getMessages()
    {
        return $this->findAll();
    }

    function getMessagesForCurrentUser()
    {

    }

    function postMessage($message)
    {
        $this->create($message);
    }

}