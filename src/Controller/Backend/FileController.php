<?php

namespace App\Controller\Backend;

use App\Entity\Files;
use App\Repository\FilesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/documents", name="file_")
 */
class FileController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(FilesRepository $filesRepository): Response
    {
        return $this->render('backend/file/index.html.twig', [
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Files $files): Response
    {
        return $this->render('backend/file/show.html.twig', [
            'files' => $files,
        ]);
    }
}
