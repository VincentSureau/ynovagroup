<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        dump($this->getUser());
        return $this->render('home/home.html.twig', [
            'current' => 'home',
        ]);
    }

    /**
     * @Route("/rgpd", name="rgpd")
     */
    public function rgpd()
    {
        return $this->render('home/rgpd.html.twig', [
            'current' => 'rgpd'
        ]);
    }
    
}