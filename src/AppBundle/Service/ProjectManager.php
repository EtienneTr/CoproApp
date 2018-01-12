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


    public function postProject($project)
    {
        $this->setOptionSurvey($project);

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

    public function postUpdate($project)
    {
        $this->setOptionSurvey($project);
        $this->update($project);
    }

    private function setOptionSurvey($project)
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
    }

    public function getUserProjects($user)
    {
        $queryBuilder = $this->repo->createQueryBuilder('p')
                        ->leftJoin('p.users', 'pu')
                        ->where('pu.id IS NULL')
                        ->orWhere(':user MEMBER OF p.users')
                        ->setParameter('user', $user)
                        ->getQuery();

        return $queryBuilder->getResult();
    }

    public function getLastProjectsForUser($user)
    {
        $queryBuilder = $this->repo->createQueryBuilder('p')
            ->leftJoin('p.users', 'pu')
            ->where('(pu.id IS NULL')
            ->orWhere(':user MEMBER OF p.users)')
            ->orWhere('p.owner = :user')
            ->orderBy('p.creationDate', 'DESC')
            ->setMaxResults(5)
            ->setParameter('user', $user)
            ->getQuery();

        return $queryBuilder->getResult();
    }

}