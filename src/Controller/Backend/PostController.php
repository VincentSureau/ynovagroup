<?php

namespace App\Controller\Backend;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/articles", name="post_")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('backend/post/index.html.twig', [
            'current' => 'article',
        ]);
    }

    /**
     * @Route("/ajouter-un-article", name="create", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $post->setDeletedAt(new \DateTime('+ 4 months'));
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post
                ->setCreatedAt(new \Datetime)
                ->setUpdatedAt(new \Datetime);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'L\'article ' . $post . ' a bien été ajouté');

            return $this->redirectToRoute('backend_post_index', []);
        }

        return $this->render('backend/post/new.html.twig', [
            'current' => 'article',
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}", requirements={"id"="\d+"}), name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {

            $post
                ->setCreatedAt(new \Datetime)
                ->setUpdatedAt(new \Datetime);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'L\'article ' . $post . ' a bien été mis à jour');

            return $this->redirectToRoute('backend_post_index', [
                'id' => $post->getId(),
            ]);
        }

        return $this->render('backend/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'current' => 'article',
        ]);
    }

    /**
     * @Route("/{id}/toggle", requirements={"id"="\d+"}, name="toggle_active", methods={"GET","POST"})
     */
    public function toggle(Post $post): Response
    {
        if($post->getIsActive() == false) {
            $post->setIsActive(true);
            $this->addFlash('success', 'l\'article ' . $post . ' a bien été mis en publié');
        } else {            
            $post->setIsActive(false);
            $this->addFlash('danger', 'l\'artlicle ' . $post . ' a bien été dépublié');
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('backend_post_index');
    }

    /**
     * @Route("/{id}/delete", requirements={"id"="\d+"}, name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();

            $this->addFlash('danger', 'L\'article ' . $post . ' a bien été supprimé');
        }

        return $this->redirectToRoute('backend_post_index');
    }
}
