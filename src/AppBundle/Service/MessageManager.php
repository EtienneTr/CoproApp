<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 08/12/2017
 * Time: 15:08
 */

namespace AppBundle\Service;

use UserBundle\Service\UserService  ;
use Doctrine\ORM\EntityManager;

class MessageManager extends CoproService
{
    private $userManager;

    public function __construct(EntityManager $entityManager, UserService $userManager)
    {
        $this->userManager = $userManager;
        parent::__construct($entityManager,"AppBundle:Message");
    }

    function getMessages()
    {
        return $this->findAll();
    }

    function getMessagesForCurrentUser()
    {
        $userId = $this->userManager->getUser()->getId();

        $req = $this->em->createQuery('SELECT m, mu FROM AppBundle:Message m LEFT JOIN m.receiver mu WHERE (mu.id = :userId OR mu.id IS NULL) AND m.sender <> :userId ORDER BY m.sendDate DESC')
            ->setParameter('userId', $userId);

        return $req->getResult();

    }

    function getMessagesFromCurrentUser()
    {
        return $this->repo->findBy(array('sender' => $this->userManager->getUser()->getId()));
    }

    function postMessage($message)
    {
        $this->create($message);
    }

    function archive($id)
    {
        $message = $this->findOne($id);
        if(!$message)
        {
            throw new Error("Message id doesn't exist");
        }

        #already archived
        if($message->getArchived() === true)
        {
            return;
        }

        $message->setArchived(true);
        $this->update($message);
    }

}