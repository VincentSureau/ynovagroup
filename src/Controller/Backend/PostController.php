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
        ]);
    }

    /**
     * @Route("/ajouter-un-article", name="create", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post
                ->setCreatedAt(new \Datetime)
                ->setUpdatedAt(new \Datetime);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('backend_post_index', []);
        }

        return $this->render('backend/post/new.html.twig', [
            'current' => 'createPost',
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {

            $post
                ->setCreatedAt(new \Datetime)
                ->setUpdatedAt(new \Datetime);
            dump($post);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_post_index', [
                'id' => $post->getId(),
            ]);
        }

        return $this->render('backend/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
}
