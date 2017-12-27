<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 14/12/2017
 * Time: 14:53
 */

namespace UserBundle\Service;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManager;
use UserBundle\Entity\User;

class UserService
{
    private $currentUser = null;
    private $token;
    private $em;
    private $repo;

    public function __construct (EntityManager $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->token = $tokenStorage->getToken();
        if($this->token)
        {
            $this->currentUser = $this->token->getUser();
        }
        #repo
        $this->em = $entityManager;
        $this->repo = $this->em->getRepository("UserBundle:User");
    }
    

    public function getUser()
    {
        return $this->currentUser;
    }

    public function getAllUsers()
    {
        return $this->repo->findAll();
    }
}