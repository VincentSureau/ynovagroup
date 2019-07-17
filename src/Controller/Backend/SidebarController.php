<?php

namespace App\Controller\Backend;

use App\Repository\FilesRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SidebarController extends AbstractController
{
    /**
     * @Route("/getSideBar", name="get_sidebar")
     */
    public function index($current = '', FilesRepository $repo, Security $security): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            $data = $repo->createQueryBuilder('f')
                        ->select('count(f.id)')
                        ->join('f.sentBy', 'u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%"ROLE_MEMBER"%')
                        ->andWhere('f.readBy IS EMPTY')
                        ->getQuery()
                        ->getSingleScalarResult();
                    ;
        } elseif ($security->isGranted('ROLE_BUSINESS')) {
            $data = $repo->createQueryBuilder('f')
                    ->select('count(f.id)')
                    ->join('f.sentBy', 'u')
                    ->where('u.roles LIKE :role')
                    ->setParameter('role', '%"ROLE_MEMBER"%')
                    ->andWhere('f.commercial = :commercial')
                    ->setParameter('commercial', $this->getUser())
                    ->andWhere('f.readBy IS EMPTY')
                    ->getQuery()
                    ->getSingleScalarResult();
                ;
        }

        return $this->render('backend/partials/_sidebar.html.twig', [
            'unread' => $data,
            'current' => $current
        ]);
    }
}
