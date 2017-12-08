<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MessageFeed
 *
 * @ORM\Table(name="message_feed")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageFeedRepository")
 */
class MessageFeed
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @var message
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Message")
     * @ORM\JoinColumn(nullable=false)
     */
    private $messages;
    /**
     * @var partOwner
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\partOwner")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @return partOwner
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param partOwner $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
    /**
     * @return message
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param message $messages
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
    }

}

