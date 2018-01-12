<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Model\Thread;

/**
 * MessageFeed
 *
 * @ORM\Table(name="message_feed")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageFeedRepository")
 */
class MessageFeed extends Thread
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var message
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Message", inversedBy="feeds")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    private $message;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param message $messages
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

}

