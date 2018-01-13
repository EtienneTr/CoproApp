<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 04/01/2018
 * Time: 09:51
 */

namespace AppBundle\Controller;

use AppBundle\Entity\File;
use AppBundle\Entity\ProjectFeed;
use AppBundle\Form\MultiFileType;
use AppBundle\Form\ProjectFeedType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
            $this->addFlash('info', "Votre projet a bien été posté.");
            return $this->redirectToRoute('project_user');
        }

        return $this->render('AppBundle:project:create_form.html.twig', array(
            'form' => $form->createView(),
            'update'=> false
        ));
    }

    /**
     * @Route("/project/all", name="project_user")
     * @Method({"GET"})
     */
    public function getProjectAction(ProjectManager $manager, UserService $userService){
        $projects = $manager->getUserProjects($userService->getUser());

        return $this->render('AppBundle:project:projects.html.twig', array(
            'projects' => $projects,
        ));
    }

    /**
     * @Route("/project/detail/{id}", name="project_detail")
     * @Method({"GET", "POST"})
     */
    public function getProjectDetailAction(Request $request, FileUploader $fileUploader, ProjectManager $projectManager, UserService $userService, $id)
    {
        $project = $projectManager->findOne($id);

        if(!$project)
        {
            $this->addFlash('danger', "Ce projet n'existe pas.");
            return $this->redirectToRoute("project_user");
        }

        if(!$project->hasAccess($userService->getUser()))
        {
            $this->addFlash('danger', "Vous ne pouvez pas accéder à cet élément.");
            return $this->redirectToRoute("project_user");
        }

        #form add attachment
        $file = new File();
        $fileForm = $this->createForm(MultiFileType::class, $file);
        $fileForm->add("save", SubmitType::class, array('label' => 'Ajouter'));

        $fileForm->handleRequest($request);

        if ($fileForm->isSubmitted() && $fileForm->isValid()) {

            #upload file
            $newFile = $fileForm->get('file')->getData();
            $fileName = $fileUploader->uploadFile($newFile);
            #add new attachement
            $project->getAttachment()->add($fileName);
            $this->addFlash('info', "Votre pièce jointe a bien été ajoutée.");
            $projectManager->update($project);
        }

        #thread form
        $newFeed = new ProjectFeed();

        $threadForm = $this->createForm(ProjectFeedType::class, $newFeed);
        $threadForm->handleRequest($request);

        if ($threadForm->isSubmitted() && $threadForm->isValid()) {

            $newFeed->setUser($userService->getUser());
            $newFeed->setProject($project);
            $newFeed->setSendDate(new \DateTime("now"));

            $project->getThread()->add($newFeed);
            $this->addFlash('info', "Votre message a bien été posté.");
            $projectManager->update($project);
        }

        return $this->render('AppBundle:project:detail.html.twig', array(
            'project' => $project,
            'fileForm' => $fileForm->createView(),
            'threadForm' => $threadForm->createView()
        ));
    }

    /**
     * @Route("/project/edit/{id}", name="project_edit")
     * @Method({"GET", "POST"})
     */
    public function editProjectAction(Request $request, ProjectManager $projectManager, UserService $userService, $id){

        $project = $projectManager->findOne($id);

        if(!$project)
        {
            $this->addFlash('danger', "Ce projet n'existe pas.");
            return $this->redirectToRoute("project_user");
        }

        if(!$project->isAuthor($userService->getUser()))
        {
            $this->addFlash('danger', "Vous ne pouvez pas effectuer cette action.");
            return $this->redirectToRoute("project_user");
        }

        $form = $this->createForm(ProjectType::class, $project, array('update' => true));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $projectManager->postUpdate($project);
            $this->addFlash('info', "Votre modification a bien été sauvegardée.");
            return $this->redirectToRoute('project_detail', array('id' => $id));
        }

        return $this->render('AppBundle:project:create_form.html.twig', array(
            'form' => $form->createView(),
            'update' => true
        ));
    }

    /**
     * @Route("/project/delete/{id}", name="project_delete")
     * @Method({"GET"})
     */
    public function deleteProjectAction(ProjectManager $projectManager, UserService $userService, $id)
    {
        $project = $projectManager->findOne($id);

        if (!$project) {
            $this->addFlash('danger', "Ce projet n'existe pas.");
            return $this->redirectToRoute("project_user");
        }

        if (!$project->isAuthor($userService->getUser())) {
            $this->addFlash('danger', "Vous ne pouvez pas effectuer cette action.");
            return $this->redirectToRoute("project_user");
        }

        $projectManager->remove($project);
        $this->addFlash('info', "Votre projet a bien été supprimé.");
        return $this->redirectToRoute('project_user');
    }

}