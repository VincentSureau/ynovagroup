<?php

namespace App\Controller\Backend;

use App\Repository\ThemeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NavbarController extends AbstractController
{
    /**
     *
    */
    public function index($current = '', ThemeRepository $repo)
    {
        $theme = $repo->findOneBy([
            'isActive' => true
        ]);

        return $this->render('backend/partials/_navbar.html.twig', [
            'theme' => $theme,
        ]);
    }
}
