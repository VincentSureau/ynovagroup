<?php

namespace App\Controller\Api;

use App\Repository\PartnerRepository;
use Symfony\Component\Security\Core\Security;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PartnerController extends FOSRestController
{
    /**
    * @Rest\Get("/api/partners")
    * @Rest\View(serializerGroups={"partner"})
    */
    public function getListAction(PartnerRepository $repo, Security $security) {
        if ($security->isGranted('ROLE_ADMIN')) {
            $data = $repo->findAll();
            return $data;
        } else {
            return [];
        }
    }
}
