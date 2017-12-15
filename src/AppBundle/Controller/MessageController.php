<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use AppBundle\Entity\Message;
use AppBundle\Service\MessageManager;
use UserBundle\Service\UserService;

class MessageController extends Controller
{
    /**
     * @Route("/message", name="message_create")
     * @Method({"GET", "POST"})
     */
    public function newMessageAction(Request $request, MessageManager $manager, UserService $userService)
    {
        $message = new Message();

        $form = $this->createFormBuilder($message)
            ->add("body", TextareaType::class)
            ->add("save", SubmitType::class, array('label' => "Créer un message" ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageForm = $form->getData();

            $message->setBody($messageForm->getBody());
            $message->setSender($userService->getUser());


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
        $messages = $manager->findAll();
        //var_dump($messages[0]);
        return $this->render('AppBundle:messages:messages.html.twig', array(
            'messages' => $messages
        ));
    }
    /**
     * @Route("/message/{id}", name="Message detail")
     * @Method({"GET"})
     */
    public function getMessageAction($id){

    }

}
