<?php

namespace App\Controller\Backend;

use App\Entity\Theme;
use App\Form\ThemeType;
use App\Repository\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/themes")
 */
class ThemeController extends AbstractController
{
    /**
     * @Route("/", name="theme_index", methods={"GET"})
     */
    public function index(ThemeRepository $themeRepository): Response
    {
        return $this->render('backend/theme/index.html.twig', [
        ]);
    }

    /**
     * @Route("/new", name="theme_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($theme);
            $entityManager->flush();

            return $this->redirectToRoute('backend_theme_index');
        }

        return $this->render('backend/theme/new.html.twig', [
            'theme' => $theme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/toggle", name="theme_toggle_active", methods={"GET","POST"})
     */
    public function toggle(Theme $theme): Response
    {
        if($theme->getIsActive() == false) {
            $theme->setIsActive(true);
            $this->addFlash('success', 'le thème du site a bien été modifié');
        } else {
            $theme->setIsActive(false);
            $this->addFlash('danger', 'le thème a bien été désactivé, attention, il n\'y a plus de thème actif sur le site');
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('backend_theme_index');
    }

    /**
     * @Route("/{id}", name="theme_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Theme $theme): Response
    {
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_theme_index', [
                'id' => $theme->getId(),
            ]);
        }

        return $this->render('backend/theme/edit.html.twig', [
            'theme' => $theme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="theme_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Theme $theme): Response
    {
        if ($this->isCsrfTokenValid('delete'.$theme->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($theme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_theme_index');
    }
}
