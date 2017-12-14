<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 14/12/2017
 * Time: 14:53
 */

namespace UserBundle\Service;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserService
{
    private $currentUser;
    private $token;

    public function __construct (TokenStorageInterface $tokenStorage)
    {
        $this->token = $tokenStorage;
        $this->currentUser = $tokenStorage->getToken()->getUser();
    }

    public function getUser()
    {
        return $this->currentUser;
    }
}