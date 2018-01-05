<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 04/01/2018
 * Time: 09:51
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Entity\Project;
use AppBundle\Service\FileUploader;
use AppBundle\Service\ProjectManager;
use UserBundle\Service\UserService;
use AppBundle\Form\ProjectType;

class ProjectController extends Controller
{
    /**
     * @Route("/project", name="project_create")
     * @Method({"GET", "POST"})
     */
    public function newProjectAction(Request $request, UserService $userService, ProjectManager $projectManager)
    {
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $project->setCreationDate(new \DateTime('now'));
            $project->setOwner($userService->getUser());

            $projectManager->postProject($project);

            return $this->redirectToRoute('project_user');
        }

        return $this->render('AppBundle:project:create_form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/project/all", name="project_user")
     * @Method({"GET"})
     */
    public function getProjectAction(ProjectManager $manager){
        $projects = $manager->getProjects();

        return $this->render('AppBundle:project:projects.html.twig', array(
            'projects' => $projects,
        ));
    }

    /**
     * @Route("/project/detail/{id}", name="project_detail")
     * @Method({"GET"})
     */
    public function getProjectDetailAction(Request $request, ProjectManager $projectManager, $id)
    {
        $project = $projectManager->findOne($id);

        if(!$project)
        {
            throw $this->createNotFoundException('The project does not exist');
        }

        return $this->render('AppBundle:project:detail.html.twig', array(
            'project' => $project
        ));
    }

}