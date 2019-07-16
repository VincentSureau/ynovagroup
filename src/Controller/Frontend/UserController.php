<?php

namespace App\Controller\Frontend;

use App\Entity\User;
use App\Entity\Files;
use App\Form\UserFilesType;
use App\Form\ChangePasswordType;
use App\Repository\FilesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/mon-espace", name="profil", methods={"GET", "POST"})
     */
    public function profil(Request $request, FilesRepository $repo): Response
    {
        $activePanel = 'actualite';
        $user = $this->getUser();
        $oldPassword = $user->getPassword();
        $documents = $repo->findUserActiveDocuments($user);

        $passwordForm = $this->createForm(ChangePasswordType::class, $user);
        if ($request->request->has('change_password')) {
            $activePanel = 'password';

            $passwordForm->handleRequest($request);
    
            if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
                $plainPassword = $passwordForm->get('plainPassword')->getData();
                if(is_null($plainPassword)){
                    $encodedPassword = $oldPassword;
                } else {
                    $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
                }

                $user->setPassword($encodedPassword);
    
                $em = $this->getDoctrine()->getManager();
                $em->flush();
    
                $this->addFlash('success','Votre mot de passe a bien été mis à jour');
            }        
        }

        $file = new Files();
        $form = $this->createForm(UserFilesType::class, $file);

        if ($request->request->has('user_files')) {
            $activePanel = 'files';
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $file
                    ->setCreatedAt(new \DateTime('now'))
                    ->setUpdatedAt(new \DateTime('now'))
                    ->setSentBy($this->getUser())
                    ->setCommercial($this->getUser()->getCompany()->getCommercial())
                    ->setIsActive(true)
                ;
    
                $em = $this->getDoctrine()->getManager();
                $em->persist($file);
                $em->flush();
    
                $this->addFlash('success','Le document a bien été transmis à votre commercial référent');
            }
        }

        return $this->render('user/profil.html.twig', [
            'user' => $user,
            'documents' => $documents,
            'current' => 'profil',
            'file' => $file,
            'form' => $form->createView(),
            'passwordForm' => $passwordForm->createView(),
            'activePanel' => $activePanel,
        ]);

        if ($this->isCsrfTokenValid('changepassword'.$user->getId(), $request->request->get('_token'))) {
        }

    }

    /**
     * @Route("/accept-cookies", name="accept_cookies", methods={"POST"})
     */
    public function acceptCookies(SessionInterface $session): JsonResponse
    {
        $session->set('acceptCookie', true);
        
        return new JsonResponse(['message' => 'les cookies ont été acceptés']);
    }
}
