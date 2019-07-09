<?php

namespace App\Controller\Backend;

use App\Entity\Files;
use App\Form\FilesType;
use App\Repository\FilesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/documents", name="file_")
 */
class FilesController extends AbstractController
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
     * @Route("/new", name="create", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $file = new Files();
        $form = $this->createForm(FilesType::class, $file);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file
                ->setCreatedAt(new \Datetime('now'))
                ->setUpdatedAt(new \Datetime('now'))
                ;
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($file);
            $entityManager->flush();

            return $this->redirectToRoute('backend_file_index');
        }

        return $this->render('/backend/file/new.html.twig', [
            'file' => $file,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Files $file): Response
    {
        $form = $this->createForm(FilesType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_file_index', [
                'id' => $file->getId(),
            ]);
        }

        return $this->render('/backend/file/edit.html.twig', [
            'file' => $file,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="files_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Files $file): Response
    {
        if ($this->isCsrfTokenValid('delete'.$file->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($file);
            $entityManager->flush();
        }

        return $this->redirectToRoute('files_index');
    }
}
