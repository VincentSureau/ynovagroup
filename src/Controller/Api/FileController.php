<?php

namespace App\Controller\Api;

use App\Repository\FilesRepository;
use Symfony\Component\Security\Core\Security;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FileController extends FOSRestController
{
    /**
    * @Rest\Get("/api/files")
    * @Rest\View(serializerGroups={"file"})
    */
    public function getListAction(FilesRepository $repo, Security $security) {
        if ($security->isGranted('ROLE_ADMIN')) {
            $data = $repo->findAll(
                ['createdAt' => 'DESC']
            );
        } elseif ($security->isGranted('ROLE_BUSINESS')) {
            $data = $refo->findBy(
                ['sentBy' => $this->getUser()]
            );
        }
        return $data;
    }
}
