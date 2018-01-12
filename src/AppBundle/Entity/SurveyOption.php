<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotNull()
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="votesNumber", type="integer", nullable=true)
     * @Assert\Type("integer")
     */
    private $votesNumber;

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
     * Set votesNumber.
     *
     * @param int $votesNumber
     *
     * @return SurveyOption
     */
    public function setVotesNumber($votesNumber)
    {
        $this->votesNumber = $votesNumber;

        return $this;
    }

    /**
     * Get votesNumber.
     *
     * @return int
     */
    public function getVotesNumber()
    {
        return $this->votesNumber;
    }

    public function increaseVotesNumber()
    {
        $this->votesNumber++;
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
