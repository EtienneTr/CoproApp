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

    function getMessagesForCurrentUser($archived)
    {
        $userId = $this->userManager->getUser()->getId();

        $req = $this->em->createQuery('SELECT m, mu FROM AppBundle:Message m 
                                      LEFT JOIN m.receiver mu 
                                      WHERE (mu.id = :userId OR mu.id IS NULL) AND m.sender <> :userId AND m.archived = :archived 
                                      ORDER BY m.sendDate DESC')
            ->setParameters(array('userId' => $userId, 'archived' => $archived));

        return $req->getResult();

    }

    function getMessagesFromCurrentUser($archived)
    {
        return $this->repo->findBy(array(
            'sender' => $this->userManager->getUser()->getId(),
            'archived' => $archived
        ), array('sendDate' => 'desc'));
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

    public function getLastMessageForUser($userId)
    {
        $qb = $this->repo
            ->createQueryBuilder("m")
            ->leftJoin('m.receiver', 'mr')
            ->where('(mr.id IS NULL')
            ->orWhere(':user MEMBER OF m.receiver)')
            ->orWhere(':user = m.sender')
            ->setParameter('user', $userId)
            ->orderBy("m.sendDate", "DESC")
            ->setMaxResults(5)
            ->getQuery();

        return $qb->getResult();
    }
}