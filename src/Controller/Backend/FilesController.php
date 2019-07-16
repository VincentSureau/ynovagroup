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
     * @Route("/ajouter-un-document", name="create", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $file = new Files();
        $file->setDeletedAt(new \DateTime('+ 4 months'));
        $form = $this->createForm(FilesType::class, $file);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file
                ->setCreatedAt(new \Datetime('now'))
                ->setUpdatedAt(new \Datetime('now'))
                ->setCommercial($this->getUser())
                ->setSentBy($this->getUser())
                ;
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($file);
            $entityManager->flush();

            $this->addFlash('success', 'Le document ' . $file . ' a bien été ajouté');

            return $this->redirectToRoute('backend_file_index');
        }

        return $this->render('/backend/file/new.html.twig', [
            'file' => $file,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id"="\d+"}, name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Files $file): Response
    {
        $form = $this->createForm(FilesType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le document ' . $file . ' a bien été mis à jour');

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
     * @Route("/{id}", requirements={"id"="\d+"}, name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Files $file): Response
    {
        if ($this->isCsrfTokenValid('delete'.$file->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($file);
            $entityManager->flush();

            $this->addFlash('success', 'Le document ' . $file . ' a bien été supprimé');
        }

        return $this->redirectToRoute('backend_file_index');
    }
}
