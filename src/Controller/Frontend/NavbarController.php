<?php

namespace App\Controller\Frontend;

use App\Repository\ThemeRepository;
use App\Repository\ConfigRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NavbarController extends AbstractController
{
    /**
     * @Route("/getNavBar", name="gat_navbar")
     */
    public function index($current = '', ThemeRepository $repo, ConfigRepository $configRepo)
    {
        $config = $configRepo->findConfig();
        $maintenance = false;

        if(!empty($config)) {
            $maintenance = $config->getIsUnderMaintenance() == true;
        }

        $theme = $repo->findOneBy([
            'isActive' => true
        ]);

        return $this->render('partials/_navbar.html.twig', [
            'theme' => $theme,
            'maintenance' => $maintenance,
            'current' => $current
        ]);
    }
}
