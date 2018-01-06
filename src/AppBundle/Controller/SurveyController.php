<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etienne
 * Date: 06/01/2018
 * Time: 10:03
 */

namespace AppBundle\Controller;


use AppBundle\Service\SurveyManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SurveyController extends  Controller
{

    /**
     * @Route("/survey/{idProject}/{idSurvey}/{idOption}", name="survey_vote")
     * @Method({"GET"})
     */
    public function voteSurveyAction(SurveyManager $surveyManager, $idProject, $idSurvey, $idOption)
    {
        #TODO check project available for user
        $surveyManager->checkAndCreateVote($idSurvey,$idOption);

        return $this->redirectToRoute("project_detail", array('id' => $idProject));

    }
}