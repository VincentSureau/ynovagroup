<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Utils\SendMail;
use App\Utils\GeneratePassword;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordCustomController extends AbstractController
{
    private $repo;

    private $passwordEncoder;

    public function __construct(UserRepository $repo, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->repo = $repo;
        $this->passwordEncoder = $passwordEncoder;
    }

    // Génère un nouveau mot de passe et envoi un mail à l'utilisateur

    public function newPassword($email, GeneratePassword $passwordFactory, SendMail $mailGenerator): Response
    {
        $user = $this->repo->findOneByEmail($email);
        if($user !== null) {
            $user->setPassword($passwordFactory->generate());
            $em = $this->getDoctrine()->getManager();
            $mailGenerator->resetPassword($user);
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);
            $em->flush();

            return $this->json($data = ["message" => "mot de passe envoyé"], $status = 200);
        }
        return $this->json($data = ["message" => "aucun utilisateur trouvé"], $status = 403);
    }
}
