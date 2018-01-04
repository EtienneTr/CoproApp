<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SurveyOption
 *
 * @ORM\Table(name="survey_option")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SurveyOptionRepository")
 */
class SurveyOption
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
     * @var int
     *
     * @ORM\Column(name="votes", type="integer", nullable=true)
     */
    private $votes;

    /**
     * @var User
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $users;

    /**
     * @var survey
     * @ORM\ManyToOne(targetEntity="Survey", inversedBy="options")
     * @ORM\JoinColumn(name="Survey_id", referencedColumnName="id")
     */
    private $survey;

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
     * @return SurveyOption
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
     * Set votes.
     *
     * @param int $votes
     *
     * @return SurveyOption
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * Get votes.
     *
     * @return int
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set user.
     *
     * @param string $user
     *
     * @return SurveyOption
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get user.
     *
     * @return string
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * @param survey $survey
     */
    public function setSurvey($survey)
    {
        $this->survey = $survey;
    }


}
