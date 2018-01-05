<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Entity\Message;
use AppBundle\Service\MessageManager;
use UserBundle\Service\UserService;
use AppBundle\Form\MessageType;

class MessageController extends Controller
{
    /**
     * @Route("/message", name="message_create")
     * @Method({"GET", "POST"})
     */
    public function newMessageAction(Request $request, MessageManager $manager, UserService $userService)
    {
        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message->setSender($userService->getUser());
            $message->setSendDate(new \DateTime("now"));
            $message->setArchived(false);

            $manager->postMessage($message);

            return $this->redirectToRoute('message_all');
        }

        return $this->render('AppBundle:messages:create_form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/message/all", name="message_all")
     * @Method({"GET"})
     */
    public function listMessageAction(MessageManager $manager)
    {
        $messages = $manager->getMessagesForCurrentUser(0);

        $userMessages = $manager->getMessagesFromCurrentUser(false);
        return $this->render('AppBundle:messages:messages.html.twig', array(
            'archived' => false,
            'messages' => $messages,
            'userMessages' => $userMessages
        ));
    }

    /**
     * @Route("/message/archived", name="message_archived")
     * @Method({"GET"})
     */
    public function archivedListMessageAction(MessageManager $manager)
    {
        $messages = $manager->getMessagesForCurrentUser(1);

        $userMessages = $manager->getMessagesFromCurrentUser(true);

        return $this->render('AppBundle:messages:messages.html.twig', array(
            'archived' => true,
            'messages' => $messages,
            'userMessages' => $userMessages
        ));
    }

    /**
     * @Route("/message/archive/{id}", name="message_archive")
     * @Method({"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function archiveMessageAction(MessageManager $manager, $id){

        $manager->archive($id);

        return $this->redirectToRoute('message_all');
    }
}
