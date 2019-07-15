<?php

namespace App\Controller\Backend;

use App\Entity\Partner;
use App\Form\PartnerType;
use App\Repository\PartnerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/partenaires", name="partner_")
 */
class PartnerController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(PartnerRepository $partnerRepository): Response
    {
        return $this->render('backend/partner/index.html.twig', [
        ]);
    }

    /**
     * @Route("/ajouter-un-partenaire", name="create", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $partner = new Partner();
        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partner
                ->setCreatedAt(new \Datetime)
                ->setUpdatedAt(new \Datetime);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($partner);
            $entityManager->flush();

            $this->addFlash('success', 'Le partenaire ' . $partner . ' a bien été ajouté');

            return $this->redirectToRoute('backend_partner_index', []);
        }

        return $this->render('backend/partner/new.html.twig', [
            'current' => 'createPartner',
            'partner' => $partner,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}", name="edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Partner $partner): Response
    {
        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le partenaire ' . $partner . ' a bien été modifié');

            return $this->redirectToRoute('backend_partner_index', [
                'id' => $partner->getId(),
            ]);
        }

        return $this->render('backend/partner/edit.html.twig', [
            'partner' => $partner,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Partner $partner): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partner->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($partner);
            $entityManager->flush();

            $this->addFlash('success', 'Le partenaire ' . $partner . ' a bien été supprimé');
        }

        return $this->redirectToRoute('backend_partner_index');
    }
}
