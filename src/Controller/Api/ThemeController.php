<?php

namespace App\Controller\Api;

use App\Repository\ThemeRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ThemeController extends FOSRestController
{
    /**
    * @Rest\Get("/api/themes")
    * @Rest\View(serializerGroups={"themes"})
    */
    public function getListAction(ThemeRepository $repo) {
        $data = $repo->findAll(
            ['name' => 'DESC']
        );

        return $data;
    }
}
