<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\Thread;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProjectFeed
 *
 * @ORM\Table(name="project_feed")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectFeedRepository")
 */
class ProjectFeed extends Thread
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
     * @var project
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="thread")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

}

