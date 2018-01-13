<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $router;
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }


    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $session = $request->getSession();
        $session->getFlashBag()->add('danger', "Vous n'avez pas accès à cette page.");

        return new RedirectResponse($this->router->generate("dashboard"));
    }
}