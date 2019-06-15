<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/profil/{slug}", name="profil", methods={"GET"})
     */
    public function profil(User $user): Response
    {
        return $this->render('user/profil.html.twig', [
            'user' => $user,
            'current' => 'profil'
        ]);
    }
}
