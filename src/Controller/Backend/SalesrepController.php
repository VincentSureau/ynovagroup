<?php

namespace App\Controller\Backend;

use App\Utils\GeneratePassword;
use App\Entity\User;
use App\Form\SalesrepType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/commerciaux", name="salesrep_")
 */
class SalesrepController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function salesrepIndex(UserRepository $userRepository): Response
    {
        return $this->render('backend/salesrep/index.html.twig', [
        ]);
    }


    /**
     * @Route("/ajout-d-un-commercial", name="create", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, GeneratePassword $passwordGenerator): Response
    {
        $user = new User();
        $form = $this->createForm(SalesrepType::class, $user);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordGenerator->generate();
            $encodedPassword = $passwordEncoder->encodePassword($user, $password);
            
            $user
            ->setRoles(['ROLE_BUSINESS'])
            ->setPassword($encodedPassword)
            ->setCreatedAt(new \Datetime)
            ->setUpdatedAt(new \Datetime);
        
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
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id"="\d+"}, name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $oldPassword = $user->getPassword();

        $form = $this->createForm(SalesrepType::class, $user);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {

            if(is_null($user->getPassword())){
                $encodedPassword = $oldPassword;

            } else {

                $encodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
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
        ]);
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

            $this->addFlash('success', 'Le commercial ' . $user . ' a bien été supprimé');
        }

        return $this->redirectToRoute('backend_salesrep_index');
    }
}
