<?php

namespace App\EventListener;


use FOS\UserBundle\Model\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserLoginRouteListener
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorageInterface;

    /**
     * @var RouterInterface
     */
    private $routerInterface;

    public function __construct(TokenStorageInterface $tokenStorageInterface, RouterInterface $routerInterface)
    {
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->routerInterface = $routerInterface;
    }

    /**
     * @param GetResponseEvent $event
     * @return bool|RedirectResponse
     * @throws \InvalidArgumentException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $routeName = $request->get('_route');
        $routesToCheck = [
            'fos_user_security_login',
            'fos_user_registration_register',
        ];

        if ( ! \in_array($routeName, $routesToCheck, true) ) {
            return false;
        }

        if ($this->tokenStorageInterface->getToken() === null) {
            return false;
        }

        if ($this->tokenStorageInterface->getToken() instanceof AnonymousToken) {
            return false;
        }

        if ( ! $this->tokenStorageInterface->getToken()->getUser() instanceof User ) {
            return false;
        }

        return $event->setResponse(
            new RedirectResponse(
                $this->routerInterface->generate('index')
            )
        );
    }
}