<?php

namespace App\Controller\Backend;

use App\Entity\Rss;
use App\Form\RssType;
use App\Repository\RssRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rss")
 */
class RssController extends AbstractController
{
    /**
     * @Route("/", name="rss_index", methods={"GET"})
     */
    public function index(RssRepository $rssRepository): Response
    {
        return $this->render('backend/rss/index.html.twig', [
            'rsses' => $rssRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajouter-un-flux-rss", name="rss_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $rss = new Rss();
        $form = $this->createForm(RssType::class, $rss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rss);
            $entityManager->flush();

            return $this->redirectToRoute('rss_index');
        }

        return $this->render('backend/rss/new.html.twig', [
            'rss' => $rss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/toggle", name="rss_toggle_active", methods={"GET","POST"})
     */
    public function toggle(Rss $rss, RssRepository $repo): Response
    {
        if($rss->getIsActive() == false) {
            $rss->setIsActive(true);
            $this->addFlash('success', 'le flux RSS ' . $rss->getName() . ' a bien été activé');
        } else {            
            $rss->setIsActive(false);
            $this->addFlash('info', 'le flux RSS ' . $rss->getName() . ' a bien été désactivé');
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('backend_rss_index');
    }

    /**
     * @Route("/{id}", name="rss_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Rss $rss): Response
    {
        $form = $this->createForm(RssType::class, $rss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rss_index', [
                'id' => $rss->getId(),
            ]);
        }

        return $this->render('backend/rss/edit.html.twig', [
            'rss' => $rss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rss_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Rss $rss): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rss->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rss);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rss_index');
    }
}