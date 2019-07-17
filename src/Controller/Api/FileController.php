<?php

namespace App\Controller\Api;

use App\Entity\Files;
use App\Repository\FilesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FileController extends FOSRestController
{
    /**
    * @Rest\Get("/api/files")
    * @Rest\View(serializerGroups={"file"})
    */
    public function getListAction(FilesRepository $repo, Security $security) {
        if ($security->isGranted('ROLE_ADMIN')) {
            $data = $repo->createQueryBuilder('f')
                        ->join('f.sentBy', 'u')
                        ->where('u.roles NOT LIKE :role')
                        ->setParameter('role', '%"ROLE_MEMBER"%')
                        ->getQuery()
                        ->getResult()
                    ;
        } elseif ($security->isGranted('ROLE_BUSINESS')) {
            $data = $repo->findBy(
                ['sentBy' => $this->getUser()]
            );
        }
        return $data;
    }

    /**
    * @Rest\Get("/api/files/received")
    * @Rest\View(serializerGroups={"receivedFile"})
    */
    public function getReceivedFilesAction(FilesRepository $repo, Security $security, UploaderHelper $helper) {
        if ($security->isGranted('ROLE_ADMIN')) {
            $data = $repo->createQueryBuilder('f')
                        ->join('f.sentBy', 'u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%"ROLE_MEMBER"%')
                        ->orderBy('f.id', 'DESC')
                        ->getQuery()
                        ->getResult()
                    ;
        } elseif ($security->isGranted('ROLE_BUSINESS')) {
            $data = $repo->createQueryBuilder('f')
                    ->join('f.sentBy', 'u')
                    ->where('u.roles LIKE :role')
                    ->setParameter('role', '%"ROLE_MEMBER"%')
                    ->andWhere('f.commercial = :commercial')
                    ->setParameter('commercial', $this->getUser())
                    ->orderBy('f.id', 'DESC')
                    ->getQuery()
                    ->getResult()
                ;
        }

        foreach ($data as $file) {
            $path = $helper->asset($file, 'documentFile');
            $file->setLink($path);
        }

        return $data;
    }

    /**
     * @Route("/api/file-downloaded/{id}", name="read_document", methods={"POST"})
     */
    public function profil(Files $file, EntityManagerInterface $em, FilesRepository $repo): JsonResponse
    {
        $user = $this->getUser();

        if(!empty($user) && !empty($file)) {
            $file->addReadBy($user);
        }

        $em->flush();

        return new JsonResponse(['message' => 'le document a été lu']);
    }
}
