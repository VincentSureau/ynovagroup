<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/actualites", name="blog")
     */
    public function index(PostRepository $postRepository, PaginatorInterface $paginator, Request $request)
    {
        $query = $postRepository->findBy(
            [
                'isActive' => true,
                'visibility' => 'public'
            ],
            ['createdAt' => 'DESC', 'updatedAt' => 'DESC']
        );

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            4/*limit per page*/
        );

        return $this->render('blog/index.html.twig', [
            'current' => 'blog',
            'pagination' => $pagination
        ]);
    }
}
