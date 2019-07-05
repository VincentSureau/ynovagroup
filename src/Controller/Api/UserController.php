<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends FOSRestController
{
    /**
    * @Rest\Get("/api/users")
    * @Rest\View(serializerGroups={"user"})
    */
    public function getListAction(UserRepository $repo) {
        $data = $repo->findMembers();

        return $data;
    }
}
