<?php

namespace AppBundle\Service;


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
}