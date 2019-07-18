<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Utils\SendMail;
use App\Form\SalesrepType;
use App\Utils\GeneratePassword;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/utilisateurs", name="salesrep_")
 */
class SalesrepController extends AbstractController
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, GeneratePassword $passwordGenerator, SendMail $mailer)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->passwordGenerator = $passwordGenerator;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function salesrepIndex(UserRepository $userRepository): Response
    {
        return $this->render('backend/salesrep/index.html.twig', [
            'current' => 'business',
        ]);
    }


    /**
     * @Route("/ajout-d-un-commercial", name="create", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(SalesrepType::class, $user);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->passwordGenerator->generate();
            $role = $form->get('role')->getData();

            $user
            ->setRoles([$role])
            ->setPassword($password)
            ->setCreatedAt(new \Datetime)
            ->setUpdatedAt(new \Datetime);
        
            $this->mailer->newCommercial($user);
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le commercial ' . $user . ' a bien été ajouté');

            return $this->redirectToRoute('backend_salesrep_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('backend/salesrep/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'current' => 'business',
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id"="\d+"}, name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $oldPassword = $user->getPassword();

        $form = $this->createForm(SalesrepType::class, $user);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {

            $role = $form->get('role')->getData();
            $user->setRoles([$role]);

            if(is_null($user->getPassword())){
                $encodedPassword = $oldPassword;

            } else {

                $encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            }

            $user->setPassword($encodedPassword);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le commercial ' . $user . ' a bien été mis à jour');

            return $this->redirectToRoute('backend_salesrep_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('backend/salesrep/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'current' => 'business',
        ]);
    }

    /**
     * @Route("/{id}/toggle", requirements={"id"="\d+"}, name="toggle_active", methods={"GET","POST"})
     */
    public function toggle(User $user): Response
    {
        if($user->getIsActive() == false) {
            $user->setIsActive(true);
            $this->addFlash('success', 'le compte ' . $user . ' a bien été activé');
        } else {            
            $user->setIsActive(false);
            $this->addFlash('danger', 'le compte ' . $user . ' a bien été désactivé');
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('backend_salesrep_index');
    }

    /**
     * @Route("/{id}", requirements={"id"="\d+"}, name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('danger', 'Le commercial ' . $user . ' a bien été supprimé');
        }

        return $this->redirectToRoute('backend_salesrep_index');
    }
}
