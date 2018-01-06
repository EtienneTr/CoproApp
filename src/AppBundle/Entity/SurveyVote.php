<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SurveyVote
 *
 * @ORM\Table(name="survey_vote")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SurveyVoteRepository")
 */
class SurveyVote
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
     * @var survey
     * @ORM\ManyToOne(targetEntity="Survey", inversedBy="votes")
     * @ORM\JoinColumn(name="survey_id", referencedColumnName="id")
     */
    private $survey;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     */
    private $user;

    /**
     * @var SurveyOption
     *
     * @ORM\ManyToOne(targetEntity="SurveyOption")
     */
    private $option;

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
     * Set survey
     *
     * @param string $survey
     *
     * @return SurveyVote
     */
    public function setSurvey($survey)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * Get survey
     *
     * @return string
     */
    public function getSurvey()
    {
        return $this->survey;
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
     * @return SurveyOption
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * @param SurveyOption $option
     */
    public function setOption($option)
    {
        $this->option = $option;
    }
}

