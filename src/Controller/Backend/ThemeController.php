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
     * @Route("/ajouter-un-theme", name="theme_new", methods={"GET","POST"})
     */
    public function new(Request $request, ThemeRepository $repo): Response
    {
        $newTheme = new Theme();
        $form = $this->createForm(ThemeType::class, $newTheme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // make sure only one theme is active by setting every other theme to false
            if($newTheme->getIsActive() == true) {
                $themes = $repo->findBy([
                    'isActive' => true
                ]);
                foreach($themes as $theme) {
                    $theme->setIsActive(false);
                }
                
                $this->addFlash('success', 'le thème ' . $theme . ' a bien été ajouté et est désormais actif');
            } else {
                $this->addFlash('success', 'Le thème ' . $theme . ' a bien été ajouté');
            }
            
            $entityManager->persist($newTheme);
            $entityManager->flush();

            return $this->redirectToRoute('backend_theme_index');
        }

        return $this->render('backend/theme/new.html.twig', [
            'theme' => $newTheme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/toggle", requirements={"id"="\d+"}, name="theme_toggle_active", methods={"GET","POST"})
     */
    public function toggle(Theme $theme, ThemeRepository $repo): Response
    {
        if($theme->getIsActive() == false) {
            // make sure only one theme is active by setting every other theme to false
            $themes = $repo->findBy([
                'isActive' => true
            ]);
            foreach($themes as $oldTheme) {
                $oldTheme->setIsActive(false);
            }

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
     * @Route("/{id}", requirements={"id"="\d+"}, name="theme_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Theme $theme, ThemeRepository $repo): Response
    {
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // make sure only one theme is active at the same time
            if($theme->getIsActive() == true) {
                $themes = $repo->findBy([
                    'isActive' => true
                ]);
                foreach($themes as $oldTheme) {
                    $oldTheme->setIsActive(false);
                }

                $theme->setIsActive(true);

                $this->addFlash('success', 'le thème ' . $theme . ' a bien été modifié et est désormais actif');
            } else {
                $this->addFlash('success', 'Le thème ' . $theme . ' a bien été mis à jour');
            }

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
     * @Route("/{id}", requirements={"id"="\d+"}, name="theme_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Theme $theme): Response
    {
        if ($this->isCsrfTokenValid('delete'.$theme->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($theme);
            $entityManager->flush();

            $this->addFlash('success', 'Le thème ' . $theme . ' a bien été supprimé');
        }

        return $this->redirectToRoute('backend_theme_index');
    }
}
