<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Service\NotificationService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class NotificationController extends Controller
{
    /**
     * @Route("/send-notification", name="send_notification")
     */
    public function sendNotification(Request $request, NotificationService $manager)
    {
        $manager->createNotification("titile", "message", "fff");

        return $this->redirectToRoute('dashboard');
    }

    /**
     * List of all notifications
     *
     * @Route("/notification", name="notif_list")
     * @Method("GET")
     */
    public function listAction(NotificationService $notifService)
    {
        $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
        $notifiable = $this->getUser()->getId();

        $userNotification = $notifService->getUserNotification($notifiableRepo, $notifiable);

        return $this->render('AppBundle:notification:notification-list.html.twig', array(
            'notifiableNotifications' => $userNotification
        ));
    }

    /**
     *
     * @Route("notification/{notifiable}/mark_as_seen/{notification}", name="notif_mark_seen")
     * @Method("POST")
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws \LogicException
     */
    public function markasseenAction($notifiable, $notification)
    {
        $manager = $this->get('mgilet.notification');
        $manager->markAsSeen(
            $manager->getNotifiableInterface($manager->getNotifiableEntityById($notifiable)),
            $manager->getNotification($notification),
            true
        );
        return $this->redirectToRoute('notif_list');
    }
    /**
     *
     * @Route("notification/{notifiable}/mark_as_unseen/{notification}", name="notif_mark_unseen")
     * @Method("POST")
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws \LogicException
     */
    public function markasunseenAction($notifiable, $notification)
    {
        $manager = $this->get('mgilet.notification');
        $manager->markAsUnseen(
            $manager->getNotifiableInterface($manager->getNotifiableEntityById($notifiable)),
            $manager->getNotification($notification),
            true
        );
        return $this->redirectToRoute('notif_list');
    }
}
