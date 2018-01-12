<?php

namespace AppBundle\Service;


use AppBundle\Entity\ChargePayement;
use AppBundle\Entity\Message;
use AppBundle\Entity\MessageFeed;
use AppBundle\Entity\Project;
use Mgilet\NotificationBundle\Manager\NotificationManager;
use UserBundle\Service\UserService;

class NotificationService
{
    private $notifManager;
    private $userService;

    public function __construct(NotificationManager $manager, UserService $userService)
    {
        $this->notifManager = $manager;
        $this->userService = $userService;
    }

    public function createUserNotification($title, $message, $link, $user)
    {
        $notif = $this->notifManager->createNotification($title);
        $notif->setMessage($message);
        $notif->setLink($link);

        $this->notifManager->addNotification(array($user), $notif, true);
    }

    public function createNotification($title, $message, $link)
    {
        $this->createUserNotification($title, $message, $link, $this->userService->getUser());
    }

    public function getUserNotification($notifiableRepo, $notifiable)
    {
        $qb = $notifiableRepo->createQueryBuilder('nn')
            ->join('nn.notification', 'n')
            ->join('nn.notifiableEntity', 'ne')
            ->where('ne.identifier = :id')
            ->orderBy('n.date', 'DESC')
            ->setParameter('id', $notifiable);

        return $qb->getQuery()->getResult();
    }


    #Cutom services
    public function createMessageNotification(Message $entity)
    {
        $sender = $entity->getSender()->getUsername();
        $users = $entity->getReceiver();
        foreach($users as $user)
        {
            $this->createUserNotification(
                "Vous avez un nouveau message.",
                $sender." vous à envoyé un message.",
                "message/detail/".$entity->getId(),
                $user
            );
        }
    }

    public function createMessageReplyNotification(MessageFeed $entity)
    {
        $message = $entity->getMessage();
        $owner = $message->getSender();
        $users = $message->getReceiver();

        $sender = $entity->getUser();
        $senderName = $sender->getUsername();

        #send to parent message author
        $this->sendFeedNotification($senderName, $message->getId(), $owner);

        #sent to message receivers
        foreach($users as $user)
        {
            if($user == $sender)
                continue;
            $this->sendFeedNotification($senderName, $message->getId(), $user);
        }
    }

    private function sendFeedNotification($userName, $messageId, $user)
    {
        $this->createUserNotification(
            "Nouvelle réponse à un message.",
            $userName." à répondu à un message que vous suivez.",
            "message/detail/".$messageId,
            $user
        );
    }

    #Cutom services
    public function createProjectNotification(Project $entity)
    {
        $sender = $entity->getOwner()->getUsername();
        $users = $entity->getUsers();
        foreach($users as $user)
        {
            $this->createUserNotification(
                "Un nouveau projet d'évolution a été créé.",
                $sender." a mis en ligne un nouveau projet.",
                "project/detail/".$entity->getId(),
                $user
            );
        }
    }

    public function createChargePaymentNotification(ChargePayement $entity)
    {
        $user = $entity->getowner();
        $this->createUserNotification(
            "Nouvelle charge vous concernant.",
            "Une nouvelle charge a été créée avec un paiment vous concernant",
            "charge/detail/".$entity->getCharge()->getId(),
            $user
        );
    }
}