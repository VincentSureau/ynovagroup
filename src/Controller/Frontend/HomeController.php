<?php

namespace App\Controller\Frontend;

use App\Form\UserMailType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PostRepository $repo, Request $request, \Swift_Mailer $mailer)
    {
        $articles = $repo->findHomeArticles();

        $form = $this->createForm(UserMailType::class);
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $content = '
                <h1>Vous avez reçu un message depuis votre site Ynovagroup.com</h1>
                <h2>Expediteur:</h2>
                <table>
                    <tr>
                        <th>Nom et Prénom</th>
                        <td>%s</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>%s</td>
                    </tr>
                </table>
                <p>contenu du message :</p>
                <p>%s</p>
            ';
            $content = sprintf($content, $data['name'], $data['email'], $data['message']);
            $message = (new \Swift_Message('Message en provenance du site Ynovagroup.com'))
                ->setFrom($data['email'])
                // set the real adresse once ine production mode
                ->setTo('hello@vincent-sureau.fr')
                // ->setTo('contact@ynovagroup.com')
                ->setBody($content, 'text/html');
            ;
        
            if($mailer->send($message)){
                $this->addFlash("success", "Votre message a bien été envoyé");
            } else {
                $this->addFlash("danger", "Votre message n'a pas été envoyé");
            }

                return $this->redirect(
                    $this->generateUrl('home') . '#contact'
                );

        }

        return $this->render('home/home.html.twig', [
            'current' => 'home',
            'articles' => $articles,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/rgpd", name="rgpd")
     */
    public function rgpd()
    {
        return $this->render('home/rgpd.html.twig', [
            'current' => 'rgpd'
        ]);
    }

    /**
     * @Route("/mentions-legales", name="mentionslegales")
     */
    public function mentionslegales()
    {
        return $this->render('home/mentionslegales.html.twig', [
            'current' => 'mentionslegales'
        ]);
    }
}