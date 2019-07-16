<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends FOSRestController
{
    /**
    * @Rest\Get("/api/users")
    * @Rest\View(serializerGroups={"user"})
    */
    public function getListAction(UserRepository $repo, Security $security) {
        if ($security->isGranted('ROLE_ADMIN')) {
            $data = $repo->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%"ROLE_MEMBER"%')
                        ->orderBy('u.id', 'ASC')
                        ->getQuery()
                        ->getResult()
                    ;
        } elseif ($security->isGranted('ROLE_BUSINESS')) {
            $data = $repo->createQueryBuilder('u')
                        ->innerJoin('u.company', 'c')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%"ROLE_MEMBER"%')
                        ->andWhere('c.commercial = :commercial')
                        ->setParameter('commercial', $this->getUser())
                        ->orderBy('u.id', 'ASC')
                        ->getQuery()
                        ->getResult()
                    ;
        }

        return $data;
    }
}
