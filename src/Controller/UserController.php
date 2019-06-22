<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/mon-espace", name="profil", methods={"GET"})
     */
    public function profil(): Response
    {
        return $this->render('user/profil.html.twig', [
            'user' => $this->getUser(),
            'current' => 'profil'
        ]);
    }
}
