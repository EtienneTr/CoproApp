<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;
use AppBundle\Enum\BankPaymentTypeEnum;

/**
 * BankPayment
 *
 * @ORM\Table(name="Bank_payment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BankPaymentRepository")
 */
class BankPayment
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
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="paymentDate", type="datetime", nullable=true)
     */
    private $paymentDate;

    /**
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="File", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $attachments;

    /**
     * @var string
     *
     * @ORM\Column(name="paymentType", type="string", length=255, nullable=true)
     */
    private $paymentType;

    /**
     * @ORM\ManyToOne(targetEntity="Charge")
     * @ORM\JoinColumn(nullable=false)
     */
    private $charge;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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
     * Set amount
     *
     * @param float $amount
     *
     * @return BankPayment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }    

    /**
     * @return \DateTime
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * @param \DateTime $paymentDate
     *
     * @return self
     */
    public function setPaymentDate(\DateTime $paymentDate)
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param string $attachments
     *
     * @return self
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @param string $paymentType
     *
     * @return self
     */
    public function setPaymentType($paymentType)
    {
        if (!in_array($paymentType, BankPaymentTypeEnum::getAvailableTypes())) {
             throw new \InvalidArgumentException("Invalid type");
         }

        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCharge()
    {
        return $this->charge;
    }

    /**
     * @param mixed $charge
     *
     * @return self
     */
    public function setCharge($charge)
    {
        $this->charge = $charge;

        return $this;
    }

    /**
     * @param User
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        
        return $this;
    }
}

