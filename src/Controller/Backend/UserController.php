<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Company;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Utils\SendMail;
use App\Utils\GeneratePassword;

/**
 * @Route("/pharmaciens", name="user_")
 */
class UserController extends AbstractController
{
    private $passwordEncoder;
    private $passwordGenerator;
    private $mailer;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, GeneratePassword $passwordGenerator, SendMail $mailer)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->passwordGenerator = $passwordGenerator;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('backend/user/index.html.twig', [
            'current' => 'user',
        ]);
    }

    /**
     * @Route("/ajout-d-un-pharmacien/{pharmacie}",requirements={"pharmacie"="\d+"}, name="create_after_company", methods={"GET","POST"})
     * @Route("/ajout-d-un-pharmacien", name="create", methods={"GET","POST"})
     */
    public function new(Company $pharmacie = null, Request $request): Response
    {
        $user = new User();
        if(!empty($pharmacie)) {
            $user->setCompany($pharmacie);
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->setRoles(['ROLE_MEMBER'])
                ->setPassword($this->passwordGenerator->generate());
            $this->mailer->newUser($user);
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);

            $em = $this->getDoctrine()->getManager();
            
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Le pharmacien '. $user . ' a bien été ajouté, un mail avec son mot de passe lui a été envoyé');

            return $this->redirectToRoute('backend_user_index', [
                'id' => $user->getId(),
            ]);
        }

        if(empty($pharmacie)) {
            $this->addFlash('warning', 'Pour changer le pharmacien d\'une pharmacie, vous devez d\'abord supprimer l\'ancien pharmacien');
        }
        
        return $this->render('backend/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'current' => 'user',
        ]);
    }

    /**
     * @Route("/{id}", name="edit", requirements={"id"="\d+"}, methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $oldPassword = $user->getPassword();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {

            if(is_null($user->getPassword())){
                $encodedPassword = $oldPassword;

            } else {

                $encodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
            }

            $user->setPassword($encodedPassword);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le pharmacien '. $user . ' a bien été mis à jour');

            return $this->redirectToRoute('backend_user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('backend/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'current' => 'user',
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

        return $this->redirectToRoute('backend_user_index');
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

            $this->addFlash('danger', 'Le pharmacien ' . $user . ' a bien été supprimé');
        }

        return $this->redirectToRoute('backend_user_index');
    }
}
