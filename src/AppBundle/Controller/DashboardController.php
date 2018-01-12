<?php

namespace AppBundle\Controller;


use AppBundle\Service\ChargeManager;
use AppBundle\Service\ChargePayementManager;
use AppBundle\Service\MessageManager;
use AppBundle\Service\ProjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Service\UserService;

class DashboardController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     * @Method({"GET"})
     */
    public function indexAction(MessageManager $messageManager, ProjectManager $projectManager,
                                ChargeManager $chargeManager, ChargePayementManager $chargePayementManager, UserService $userService)
    {
        $user = $userService->getUser();
        $userMessages = $messageManager->getLastMessageForUser($user);
        $userProjects = $projectManager->getLastProjectsForUser($user);
        $userCharges = $chargeManager->getLastChargesForUser($user);
        $userPayments = $chargePayementManager->getLastUnpaidPaymentsForUser($user);

        return $this->render('AppBundle:dashboard:dashboard.html.twig', array(
            'messages'=> $userMessages,
            'projects' => $userProjects,
            'userCharge' => $userCharges,
            'userPayments' => $userPayments
        ));
    }

}
