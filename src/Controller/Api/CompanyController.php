<?php

namespace App\Controller\Api;

use App\Repository\CompanyRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompanyController extends FOSRestController
{
    /**
    * @Rest\Get("/api/companies")
    * @Rest\View(serializerGroups={"company"})
    */
    public function getListAction(CompanyRepository $repo) {
        $data = $repo->findAll(
            ['createdAt' => 'DESC']
        );

        return $data;
    }
}
