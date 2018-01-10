<?php

namespace AppBundle\Controller;

use AppBundle\Service\NotificationService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->render('AppBundle:dashboard:dashboard.html.twig', array());
    }

    /**
     * @Route("/send-notification", name="send_notification")
     */
    public function sendNotification(Request $request, NotificationService $manager)
    {
        $manager->createUserNotification("titile", "message", "fff");

        return $this->redirectToRoute('dashboard');
    }

    /**
     * List of all notifications
     *
     * @Route("/notification", name="notif_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
        $notifiable = $this->getUser()->getId();
        $qb =  $notifiableRepo->createQueryBuilder('nn')
            ->join('nn.notification', 'n')
            ->join('nn.notifiableEntity', 'ne')
            ->where('ne.identifier = :id')
            ->setParameter('id', $notifiable)
            ->getQuery()->getResult();

        return $this->render('MgiletNotificationBundle::notifications.html.twig', array(
            'notifiableNotifications' => $qb
        ));
    }
}
