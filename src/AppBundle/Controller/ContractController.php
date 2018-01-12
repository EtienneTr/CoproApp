<?php

namespace AppBundle\Controller;

use AppBundle\Form\ContractType;
use AppBundle\Service\ContractManager;
use AppBundle\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Entity\Contract;
use UserBundle\Service\UserService;

class ContractController extends Controller
{
    /**
     * @Route("/contract", name="contract_create")
     * @Method({"GET", "POST"})
     */
    public function newContractAction(Request $request, ContractManager $manager, FileUploader $fileUploader)
    {
        $contract = new Contract();

        $form = $this->createForm(ContractType::class, $contract);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contract->setCreationDate(new \DateTime("now"));
            $attachment = $form->get('attachment')->getData();
            if($attachment)
            {
                $attName = $fileUploader->uploadFile($attachment);
                $contract->setAttachment($attName);
            }
            $manager->postContract($contract);

            return $this->redirectToRoute('contract_all');
        }

        return $this->render('AppBundle:contract:create_form.html.twig', array(
            'form' => $form->createView(),
            'update' => false
        ));
    }

    /**
     * @Route("/contract/all", name="contract_all")
     * @Method({"GET"})
     */
    public function listMessageAction(ContractManager $manager,  UserService $userService)
    {
        $contracts = $manager->getUserContracts($userService->getUser()->getId());

        return $this->render('AppBundle:contract:contracts.html.twig', array(
            'contracts' => $contracts,
        ));
    }

    /**
     * @Route("/contract/edit/{id}", name="contract_edit")
     * @Method({"GET", "POST"})
     */
    public function editMessageAction(Request $request, ContractManager $contractManager, UserService $userService, FileUploader $fileUploader, $id){

        $contract = $contractManager->findOne($id);

        if(!$contract)
        {
            $this->addFlash('danger', "Ce contrat n'existe pas.");
            return $this->redirectToRoute("contract_all");
        }

        if(!$contract->isMember($userService->getUser()))
        {
            $this->addFlash('danger', "Vous ne pouvez pas accéder à ce contrat.");
            return $this->redirectToRoute("contract_all");
        }

        $form = $this->createForm(ContractType::class, $contract, array('update' => true));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            #replacing attachment
            $attachment = $form->get('attachment')->getData();
            if($attachment)
            {
                $attName = $fileUploader->uploadFile($attachment);
                $contract->setAttachment($attName);
            }
            $contractManager->update($contract);

            $this->addFlash('info', "le contrat a été correctement enregistré.");

            return $this->redirectToRoute('contract_all');
        }

        return $this->render('AppBundle:contract:create_form.html.twig', array(
            'form' => $form->createView(),
            'update' => true
        ));
    }

    /**
     * @Route("/contract/delete/{id}", name="contract_delete")
     * @Method({"GET"})
     */
    public function deleteMessageAction(ContractManager $contractManager, UserService $userService, $id)
    {
        $contract = $contractManager->findOne($id);

        if (!$contract) {
            $this->addFlash('danger', "Ce contrat n'existe pas.");
            return $this->redirectToRoute("contract_all");
        }

        if (!$contract->isMember($userService->getUser())) {
            $this->addFlash('danger', "Vous ne pouvez pas effectuer cette action.");
            return $this->redirectToRoute("contract_all");
        }

        $contractManager->remove($contract);
        $this->addFlash('info', "le contrat a été correctement supprimé.");
        return $this->redirectToRoute('contract_all');
    }
}
