<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Files;
use App\Form\UserFilesType;
use App\Repository\FilesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/mon-espace", name="profil", methods={"GET", "POST"})
     */
    public function profil(Request $request, FilesRepository $repo): Response
    {
        $user = $this->getUser();
        $documents = $repo->findUserActiveDocuments($user);

        $file = new Files();
        $form = $this->createForm(UserFilesType::class, $file);
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

        return $this->render('user/profil.html.twig', [
            'user' => $user,
            'documents' => $documents,
            'current' => 'profil',
            'file' => $file,
            'form' => $form->createView(),
        ]);
    }
}
