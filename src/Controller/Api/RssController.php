<?php

namespace App\Controller\Api;

use App\Repository\RssRepository;
use Symfony\Component\Security\Core\Security;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RssController extends FOSRestController
{
    /**
    * @Rest\Get("/api/rss")
    * @Rest\View(serializerGroups={"rss"})
    */
    public function getListAction(RssRepository $repo, Security $security) {
        if ($security->isGranted('ROLE_ADMIN')) {
            $data = $repo->findAll(
                ['name' => 'DESC']
            );
        } 

        return $data;
    }
}
