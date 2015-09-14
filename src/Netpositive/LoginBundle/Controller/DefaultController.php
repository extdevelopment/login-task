<?php

namespace Netpositive\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('NetpositiveLoginBundle:Default:index.html.twig', array('name' => $name));
    }
}
