<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * Survey
 *
 * @ORM\Table(name="survey")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SurveyRepository")
 */
class Survey
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var SurveyOption
     *
     * @ORM\OneToMany(targetEntity="SurveyOption", mappedBy="survey", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $options;

    /**
     * @var SurveyVotes
     *
     * @ORM\OneToMany(targetEntity="SurveyVote", mappedBy="survey", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $votes;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Survey
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set options.
     *
     * @param string $options
     *
     * @return Survey
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get options.
     *
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return SurveyVotes
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * @param SurveyVotes $votes
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;
    }

    public function hasVoted($user)
    {
        #get votes for user
        $votes = $this->getVotes();
        $criteria = Criteria::create()->where(Criteria::expr()->eq('user', $user));
        return $votes->matching($criteria)->toArray();
    }
}
