<?php

namespace App\Controller\Frontend;

use App\Repository\ThemeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NavbarController extends AbstractController
{
    /**
     * @Route("/getNavBar", name="gat_navbar")
     */
    public function index($current = '', ThemeRepository $repo)
    {
        $theme = $repo->findOneBy([
            'isActive' => true
        ]);

        return $this->render('partials/_navbar.html.twig', [
            'theme' => $theme,
            'current' => $current
        ]);
    }
}
