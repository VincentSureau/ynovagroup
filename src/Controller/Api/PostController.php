<?php

namespace App\Controller\Api;

use App\Repository\PostRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends FOSRestController
{
    /**
    * @Rest\Get("/api/posts")
    * @Rest\View(serializerGroups={"post"})
    */
    public function getListAction(PostRepository $repo) {
        $data = $repo->findAll();

        return $data;
    }
}
