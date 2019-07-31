<?php

namespace App\Utils;

class SendMail
{
  private $mailer;

  public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
  {
      $this->mailer = $mailer;
      $this->twig = $twig;
  }

  /**
   * Send a welcoming mail to every member with his password
   *
   * @param [type] $user
   * @return mail
   */
  public function newUser($user)
  {
    $message = (new \Swift_Message('Bienvenue sur Ynovagroup.com'))
        ->setFrom('contact@ynovagroup.com')
        ->setTo($user->getEmail())
        ->setBody($this->twig->render('email/registration.html.twig', ['user' => $user]), 'text/html');
    return $this->mailer->send($message);
  }

  /**
   * Send a welcoming mail to every commercial with his password
   *
   * @param [type] $user
   * @return mail
   */
  public function newCommercial($user)
  {
    $message = (new \Swift_Message('Bienvenue sur Ynovagroup.com'))
        ->setFrom('contact@ynovagroup.com')
        ->setTo($user->getEmail())
        ->setBody($this->twig->render('email/registrationCommercial.html.twig', ['user' => $user]), 'text/html');
    return $this->mailer->send($message);
  }

  // send a mail to a user with a new password
  public function resetPassword($user)
  {
    $message = (new \Swift_Message('Votre nouveau mot de passe sur Ynovagroup.com'))
        ->setFrom('contact@ynovagroup.com')
        ->setTo($user->getEmail())
        ->setBody($this->twig->render('email/resetpassword.html.twig', ['user' => $user]), 'text/html');

    return $this->mailer->send($message);
  }

  // send a mail to user with the newsletter
  public function sendNewsletter($user, $newPosts)
  {
    $message = (new \Swift_Message('Votre newsletter novagroup.com'))
        ->setFrom('contact@ynovagroup.com')
        ->setTo($user->getEmail())
        ->setBody($this->twig->render('email/tuesdayNewsletter.html.twig', ['user' => $user, 'newPosts' => $newPosts]), 'text/html');

    return $this->mailer->send($message);
  }
}
