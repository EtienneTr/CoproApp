<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Versement
 *
 * @ORM\Table(name="versement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VersementRepository")
 */
class Versement
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
     * @return Versement
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
}

