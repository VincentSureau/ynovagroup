<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\FilesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/mon-espace", name="profil", methods={"GET"})
     */
    public function profil(FilesRepository $repo): Response
    {
        $user = $this->getUser();

        $documents = $repo->findUserActiveDocuments($user);

        return $this->render('user/profil.html.twig', [
            'user' => $user,
            'documents' => $documents,
            'current' => 'profil'
        ]);
    }
}
