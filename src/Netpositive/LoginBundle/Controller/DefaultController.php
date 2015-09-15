<?php

namespace Netpositive\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

/**
 * DefaultController.
 *
 * @author zsolt.kobor
 */
class DefaultController extends Controller
{
    /**
     * @throws AccessDeniedException
     *
     * @return Response A Response instance
     */
    public function indexAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('NetpositiveLoginBundle:Default:index.html.twig', array('user' => $user));
    }
}
