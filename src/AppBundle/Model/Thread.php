<?php

namespace AppBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;

/**
 * Thread
 *
 * @ORM\MappedSuperclass
 */
abstract class Thread
{
    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    protected $body;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sendDate", type="datetime", nullable=true)
     */
    protected $sendDate;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return \DateTime
     */
    public function getSendDate()
    {
        return $this->sendDate;
    }

    /**
     * @param \DateTime $sendDate
     */
    public function setSendDate($sendDate)
    {
        $this->sendDate = $sendDate;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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

