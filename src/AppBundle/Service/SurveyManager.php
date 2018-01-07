<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 08/12/2017
 * Time: 15:08
 */

namespace AppBundle\Service;

use AppBundle\Entity\SurveyVote;
use Doctrine\ORM\EntityManager;
use Psr\Log\InvalidArgumentException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use UserBundle\Service\UserService;

class SurveyManager extends CoproService
{
    private $userService;
    private $optionService;

    public function __construct(EntityManager $entityManager, UserService $userService)
    {
        $this->userService = $userService;
        $this->optionService = new CoproService($entityManager, "AppBundle:SurveyOption");

        parent::__construct($entityManager,"AppBundle:Survey");
    }

    public function getSurvey($id)
    {
        return $this->findOne($id);
    }

    public function checkAndCreateVote($idSurvey, $idOption)
    {
        $survey = $this->getSurvey($idSurvey);

        if(!$survey)
        {
            throw new InvalidArgumentException("Erreur lors de la récupération du sondage");
        }
        #check option exist in surveys
        $optionArr = $this->getSurveyOption($idSurvey, $idOption);
        $option = array_pop($optionArr);
        if(!$option)
        {
            throw new InvalidArgumentException("Erreur lors de la récupération du vote");
        }

        #check if user can vote
        $currentUser = $this->userService->getUser();

        if($this->checkUserCanVote($idSurvey, $currentUser->getId()) != null)
        {
            throw new InvalidArgumentException("Vous avez déjà voté.");
        }

        $this->createVote($survey, $currentUser, $option);
    }

    private function createVote($survey, $user, $option)
    {
        $vote = new SurveyVote();
        $vote->setUser($user);
        $vote->setSurvey($survey);
        $vote->setOption($option);

        $survey->getVotes()->add($vote);
        $this->update($survey);

        $option->increaseVotesNumber();
        $this->optionService->update($option);
    }

    private function getSurveyOption($idSurvey, $idOption)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('so')
            ->from('AppBundle:SurveyOption', 'so')
            ->where('so.id = :idOption and so.survey = :idSurvey')
            ->setParameters(array(
                'idOption' => $idOption,
                'idSurvey' => $idSurvey
            ));

        return $qb->getQuery()->getResult();
    }

    private function checkUserCanVote($idSurvey, $idUser)
    {
        $req = $this->em->createQuery(
            'SELECT s FROM AppBundle:Survey s LEFT JOIN s.votes so 
                 WHERE so.survey = s.id AND so.user = :userId AND s.id = :surveyId')
            ->setParameters(array(
                'userId' => $idUser,
                'surveyId' => $idSurvey
            ));

        return $req->getResult();

    }


}