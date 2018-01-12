<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;
use UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Charge
 *
 * @ORM\Table(name="charge")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChargeRepository")
 */
class Charge
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
     * @Assert\Type("float")
     * @Assert\GreaterThan(0)
     */
    private $amount;
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotNull()
     */
    private $title;

    /**
     * @var date
     *
     * @ORM\Column(name="due_on", type="date")
     * @Assert\GreaterThan("today")
     * @Assert\NotNull()
     */
    private $dueOn;

    /**
     * @var Date
     *
     * @ORM\Column(name="creation_date", type="date")
     */
    private $creationDate;

    /**
     * @var ChargePayement
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ChargePayement", mappedBy="charge", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $payments;
    
    /**
     * @var User
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $owners;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $paid;

    /**
     * @var Contract
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Contract")
     * @ORM\JoinColumn(nullable=true)
     */
    private $contract;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\File", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $bill;

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
     * @return Charge
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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return Date
     */
    public function getDueOn()
    {
        return $this->dueOn;
    }

    /**
     * @param Date $dueOn
     */
    public function setDueOn($dueOn)
    {
        $this->dueOn = $dueOn;
    }

    /**
     * @return Date
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param Date $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return ChargePayement
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @param User $payment
     */
    public function setPayments($payments)
    {
        $this->payments = $payments;
    }

    /**
     * @return User
     */
    public function getOwners()
    {
        return $this->owners;
    }

    /**
     * @param User $owners
     */
    public function setOwners($owners)
    {
        $this->owners = $owners;
    }

    /**
     * @return string
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * @param mixed $paid
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;
    }

    /**
     * @return Contract
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @param Contract $contract
     */
    public function setContract($contract)
    {
        $this->contract = $contract;
    }

    /**
     * @return mixed
     */
    public function getBill()
    {
        return $this->bill;
    }

    /**
     * @param mixed $bill
     */
    public function setBill($bill)
    {
        $this->bill = $bill;
    }

    public function hasAccess(User $user)
    {
        $members = $this->getOwners();
        if(sizeof($members) <= 0) return true;

        return is_array($members) ? in_array($user, $members) : $members->contains($user);
    }


}

