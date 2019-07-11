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
     * @Route("/partenaires", name="partenaires")
     */
    public function partenaires()
    {
        return $this->render('home/partenaires.html.twig', [
            'current' => 'partenaires'
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

    /**
     * @Route("/mentions-legales", name="mentionslegales")
     */
    public function mentionslegales()
    {
        return $this->render('home/mentionslegales.html.twig', [
            'current' => 'mentionslegales'
        ]);
    }
    
}