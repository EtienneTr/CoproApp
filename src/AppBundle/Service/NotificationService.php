<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 10/01/2018
 * Time: 19:34
 */

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

    public function createUserNotification($title, $message, $link)
    {
        $notif = $this->notifManager->createNotification($title);
        $notif->setMessage($message);
        $notif->setLink($link);

        $this->notifManager->addNotification(array($this->userService->getUser()), $notif, true);

    }
}