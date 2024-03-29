<?php

namespace App\Controller\Api;

use App\Repository\PostRepository;
use Symfony\Component\Security\Core\Security;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends FOSRestController
{
    /**
    * @Rest\Get("/api/posts")
    * @Rest\View(serializerGroups={"post"})
    */
    public function getListAction(PostRepository $repo, Security $security) {
        if ($security->isGranted('ROLE_ADMIN')) {
            $data = $repo->findAll();
            return $data;
        } else {
            return [];
        }
    }
}
