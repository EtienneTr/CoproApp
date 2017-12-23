<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etienne
 * Date: 23/12/2017
 * Time: 15:43
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Entity\Message;
use AppBundle\Entity\MessageFeed;
use AppBundle\Service\MessageManager;
use AppBundle\Service\MessageFeedManager;
use UserBundle\Service\UserService;

class FeedsController extends Controller
{
    /**
     * @Route("/message/detail/{id}", name="message_detail")
     * @Method({"GET", "POST"})
     */
    public function getMessageDetail(Request $request, MessageManager $messageManager, MessageFeedManager $feedManager
        , UserService $userService, $id)
    {
        $message = $messageManager->findOne($id);

        $newFeed = new MessageFeed();
        $newFeed->setMessage($message);

        $form = $this->createFormBuilder($newFeed)
            ->add("body", TextareaType::class)
            ->add("save", SubmitType::class, array('label' => "Répondre au message" ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newFeed->setUser($userService->getUser());
            $newFeed->setSendDate(new \DateTime("now"));

            $feedManager->postMessage($newFeed);

            return $this->redirect($request->getUri());
        }

        return $this->render('AppBundle:messages:detail.html.twig', array(
            'form' => $form->createView(),
            'message' => $message
        ));

    }
}