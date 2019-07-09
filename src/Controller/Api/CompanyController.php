<?php

namespace App\Controller\Api;

use App\Repository\CompanyRepository;
use Symfony\Component\Security\Core\Security;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompanyController extends FOSRestController
{
    /**
    * @Rest\Get("/api/companies")
    * @Rest\View(serializerGroups={"company"})
    */
    public function getListAction(CompanyRepository $repo, Security $security) {
        if ($security->isGranted('ROLE_ADMIN')) {
            $data = $repo->findAll(
                ['createdAt' => 'DESC']
            );
        } elseif ($security->isGranted('ROLE_BUSINESS')) {
            $data = $repo->findBy(
                ['commercial' => $this->getUser()],
                ['name' => 'ASC', 'createdAt' => 'DESC']
            );
        }

        return $data;
    }
}
