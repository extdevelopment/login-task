<?php

namespace Netpositive\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('NetpositiveLoginBundle:Default:index.html.twig', array());
    }
}
