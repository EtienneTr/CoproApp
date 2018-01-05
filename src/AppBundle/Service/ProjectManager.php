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
    private $fileUploader;

    public function __construct(EntityManager $entityManager, FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;

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

        $files = $project->getAttachment();
        $newFiles = [];
        foreach ($files as $file)
        {
            #multi-files case
            if(is_array($file)) {
                $file = array_shift($file);
            }
            $fileName = $this->fileUploader->uploadFile($file);
            array_push($newFiles, $fileName);
        }
        $project->setAttachment($newFiles);
        $this->create($project);
    }

    function getProjects()
    {
        return $this->findAll();
    }

}