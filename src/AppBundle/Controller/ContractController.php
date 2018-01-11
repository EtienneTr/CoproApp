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
            throw $this->createNotFoundException('Ce projet n\'existe pas');
        }

        if(!$contract->isMember($userService->getUser()))
        {
            throw $this->createAccessDeniedException();
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
            return $this->redirectToRoute('contract_all');
        }

        return $this->render('AppBundle:contract:create_form.html.twig', array(
            'form' => $form->createView(),
            'update' => true
        ));
    }
}
