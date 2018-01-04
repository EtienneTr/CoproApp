<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 08/12/2017
 * Time: 15:08
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class ProjectManager extends CoproService
{

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager,"AppBundle:Project");
    }


    function postProject($project)
    {
        $surveys = $project->getSurvey();
        foreach($surveys as $surv)
        {
            $options = $surv->getOptions();
            foreach($options as $opt)
            {
                $opt->setSurvey($surv);
            }
        }

        $this->create($project);
    }

    function getProjects()
    {
        return $this->findAll();
    }

}