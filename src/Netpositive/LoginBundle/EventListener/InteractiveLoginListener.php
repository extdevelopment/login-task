<?php

namespace Netpositive\LoginBundle\EventListener;

use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * SecurityListener.
 *
 * @author zsolt.k
 */
class InteractiveLoginListener
{
    protected $userManager;
    protected $requestStack;

    /**
     * @param UserManagerInterface $userManager
     * @param RequestStack         $requestStack
     *
     * @link https://symfony.com/blog/new-in-symfony-2-4-the-request-stack
     */
    public function __construct(UserManagerInterface $userManager, RequestStack $requestStack)
    {
        $this->userManager = $userManager;
        $this->requestStack = $requestStack;
    }

    /**
     * handle user Ip log to users table, after login, with security.interactive_login kernel event.
     *
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof UserInterface) {
            $ipAddress = $this
                ->requestStack
                ->getCurrentRequest()
                ->server
                ->get('REMOTE_ADDR');

            $user->setLastloginClientIp($ipAddress);
            $this->userManager->updateUser($user);
        }
    }
}
