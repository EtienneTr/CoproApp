<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends Controller
{
    /**
     * @Route("/Message", name="Message list", requirements={methods: [GET]})
     */
    public function listMessageAction()
    {
        return $this->render('');
    }
    /**
     * @Route("/Message/{id}", name="Message detail", requirements={methods: [GET]})
     */
    public function getMessageAction($id){

    }
    /**
     * @Route("/Message", name="Add Message", requirements={methods: [POST]})
     */
    public function postMessageAction(){

    }



}
