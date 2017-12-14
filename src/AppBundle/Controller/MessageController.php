<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class MessageController extends Controller
{
    /**
     * @Route("/message", name="Message list")
     * @Method({"GET"})
     */
    public function listMessageAction()
    {
        return $this->render('');
    }
    /**
     * @Route("/message/{id}", name="Message detail")
     * @Method({"GET"})
     */
    public function getMessageAction($id){

    }
    /**
     * @Route("/message", name="Add Message")
     * @Method({"POST"})
     */
    public function postMessageAction(){

    }



}
