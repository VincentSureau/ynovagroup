<?php

namespace App\Controller\Api;

use App\Repository\ThemeRepository;
use Symfony\Component\Security\Core\Security;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ThemeController extends FOSRestController
{
    /**
    * @Rest\Get("/api/themes")
    * @Rest\View(serializerGroups={"themes"})
    */
    public function getListAction(ThemeRepository $repo, Security $security) {

        if($security->isGranted('ROLE_ADMIN')) {
            $data = $repo->findAll(
                ['name' => 'DESC']
            );
        }

        return $data;
    }
}
