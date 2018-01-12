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
            $this->addFlash('danger', "Ce projet n'existe pas.");
            return $this->redirectToRoute("project_user");
        }

        if(!$project->hasAccess($userService->getUser()))
        {
            $this->addFlash('danger', "Vous ne pouvez pas effectuer cette action.");
            return $this->redirectToRoute("project_user");
        }
        #catch errors from service for flash message view
        try {
            $surveyManager->checkAndCreateVote($idSurvey, $idOption);
        }
        catch(\Exception $exception)
        {
            $this->addFlash('danger', $exception->getMessage());
        }
        finally
        {
            return $this->redirectToRoute("project_detail", array('id' => $idProject));
        }

    }
}