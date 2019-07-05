<?php

namespace App\Controller\Api;

use App\Repository\FilesRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FileController extends FOSRestController
{
    /**
    * @Rest\Get("/api/files")
    * @Rest\View(serializerGroups={"file"})
    */
    public function getListAction(FilesRepository $repo) {
        $data = $repo->findAll(
            ['createdAt' => 'DESC']
        );

        return $data;
    }
}
