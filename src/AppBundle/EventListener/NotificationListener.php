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
            $sender = $entity->getSender()->getUsername();
            $users = $entity->getReceiver();
            foreach($users as $user)
            {
                $notifService->createUserNotification(
                    "Vous avez un nouveau message.",
                    $sender." vous à envoyé un message.",
                    "message/detail/".$entity->getId(),
                    $user
                );
            }
        }

        if($entity instanceof MessageFeed)
        {
            $message = $entity->getMessage();
            $owner = $message->getSender();
            $users = $message->getReceiver();

            $sender = $entity->getUser();
            $senderName = $sender->getUsername();

            #send to parent message author
            $this->sendFeedNotification($notifService, $senderName, $message->getId(), $owner);

            #sent to message receivers
            foreach($users as $user)
            {
                if($user == $sender)
                    continue;
                $this->sendFeedNotification($notifService, $senderName, $message->getId(), $user);
            }

        }

        return;
    }

    private function sendFeedNotification($service, $userName, $messageId, $user)
    {
        $service->createUserNotification(
            "Nouvelle réponse à un message.",
            $userName." à répondu à un message que vous suivez.",
            "message/detail/".$messageId,
            $user
        );
    }

}