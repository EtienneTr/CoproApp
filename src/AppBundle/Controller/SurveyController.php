<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etienne
 * Date: 06/01/2018
 * Time: 10:03
 */

namespace AppBundle\Controller;


use AppBundle\Service\ProjectManager;
use AppBundle\Service\SurveyManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Service\UserService;

class SurveyController extends  Controller
{

    /**
     * @Route("/survey/{idProject}/{idSurvey}/{idOption}", name="survey_vote")
     * @Method({"GET"})
     */
    public function voteSurveyAction(SurveyManager $surveyManager, ProjectManager $projectManager, UserService $userService, $idProject, $idSurvey, $idOption)
    {
        $project = $projectManager->findOne($idProject);
        if(!$project)
        {
            throw $this->createNotFoundException('Ce projet n\'existe pas.');
        }

        if(!$project->hasAccess($userService->getUser()))
        {
            throw $this->createAccessDeniedException();
        }

        $surveyManager->checkAndCreateVote($idSurvey,$idOption);

        return $this->redirectToRoute("project_detail", array('id' => $idProject));

    }
}