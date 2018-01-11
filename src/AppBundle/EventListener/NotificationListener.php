<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 18/12/2017
 * Time: 10:55
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Message;
use AppBundle\Entity\MessageFeed;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;


class NotificationListener
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $notifService = $this->container->get('AppBundle\Service\NotificationService');

        #message notification
        if ($entity instanceof Message) {
            $notifService->createMessageNotification($entity);
        }

        #message reply notification
        if($entity instanceof MessageFeed)
        {
            $notifService->createMessageReplyNotification($entity);
        }

        return;
    }

}