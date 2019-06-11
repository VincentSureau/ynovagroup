<?php

namespace App\Controller\Backend;

use App\Entity\Files;
use App\Form\FilesType;
use App\Repository\FilesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/file", name="file_")
 */
class FileController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(FilesRepository $filesRepository)
    {
        return $this->render('backend/file/index.html.twig', [
        ]);
    }
}
