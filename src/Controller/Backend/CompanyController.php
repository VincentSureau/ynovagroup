<?php

namespace App\Controller\Backend;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\UserRepository;
use App\Repository\CompanyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/pharmacies", name="company_")
 */
class CompanyController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CompanyRepository $companyRepository): Response
    {
        return $this->render('backend/company/index.html.twig', [
            'current' => 'company'
        ]);
    }
    
    /**
     * @Route("/ajouter-une-pharmacie", name="create", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $company = new Company();

        $company->setCountry('France');

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {

            $company
                ->setCreatedAt(new \Datetime())
                ->setUpdatedAt(new \Datetime())
                ->setIsActive(true)
            ;

            $em = $this->getDoctrine()->getManager();
            
            $em->persist($company);
            $em->flush();

            $this->addFlash('success', 'La pharmacie ' . $company . ' a bien été ajoutée, Vous devez maintenant créer un compte gestionnaire');

            return $this->redirectToRoute('backend_user_create_after_company', [
                'pharmacie' => $company->getId(),
            ]);
        }

        $this->addFlash('warning', 'Après avoir créé la pharmacie, vous pourrez ajouter un compte utilisateur');

        return $this->render('backend/company/new.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
            'current' => 'company',
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id"="\d+"}, name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Company $company): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            $company
                ->setUpdatedAt(new \Datetime())
            ;
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La pharmacie ' . $company . ' a bien été mise à jour');

            return $this->redirectToRoute('backend_company_index', [
                'id' => $company->getId(),
            ]);
        }

        return $this->render('backend/company/edit.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
            'current' => 'company',
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id"="\d+"}, name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Company $company, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
            $users = $userRepository->findByCompany($company);
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($company);
            $entityManager->flush();

            $this->addFlash('danger', 'La pharmacie ' . $company . ' a bien été supprimée');
        }

        return $this->redirectToRoute('backend_company_index');
    }
}
