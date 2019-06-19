<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'current' => 'login'
            ]);
    }

    // Génère un nouveau mot de passe et envoi un mail à l'utilisateur

    /**
     * @Route("/mot-de-passe-oublie", name="app_forgotten_pasword")
     */
    public function newPassword(Request $request, UserPasswordEncoderInterface $encoder, UserRepository $repo): Response
    {
        $form = $this->createForm(PasswordType::class);
        $data = $request->request->all();
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {        
            $email = $form->get('email')->getData();
            $user = $repo->findOneByEmail($email);

            if($user) {
                // encode the plain password
                $user->setPassword(
                    $encoder->encodePassword(
                        $user,
                        'admin'
                    )
                );

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Un nouveau mot de passe vous a été envoyé par mail'
                );
                // do anything else you need here, like send an email

                return $this->redirectToRoute('app_login');
            }
            
            $this->addFlash(
                'danger',
                'L\'email saisi est incorrect'
            );

        }
        return $this->render('security/password.html.twig', [
            'current' => 'password'
            ]);
    }
}
