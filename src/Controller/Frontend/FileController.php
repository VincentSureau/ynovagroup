<?php

namespace App\Controller\Frontend;

use App\Entity\User;
use App\Entity\Files;
use App\Repository\FilesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FileController extends AbstractController
{
    /**
     * @Route("/file-read/{id}", name="read_document", methods={"POST"})
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
