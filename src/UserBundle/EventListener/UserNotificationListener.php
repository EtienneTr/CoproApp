<?php

namespace UserBundle\EventListener;

use AppBundle\Service\NotificationService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UserBundle\Entity\User;

class UserNotificationListener
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $notifService = $this->container->get('AppBundle\Service\NotificationService');

        $entity = $args->getEntity();
        #Create register notification
        if ($entity instanceof User) {
            $userName = $entity->getUsername();
            $notifService->createUserNotification(
                "Bienvenue ".$userName,
                "Votre compte a bien été enregistré.",
                "",
                $entity
            );
        }

        return;
    }

}