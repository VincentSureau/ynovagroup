<?php

namespace App\Controller\Backend;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
        ]);
    }
    
    /**
     * @Route("/ajouter-une-pharmacie", name="create", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $company = new Company();

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {

            $company
                ->setCreatedAt(new \Datetime())
                ->setUpdatedAt(new \Datetime())
            ;
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_company_index', [
                'id' => $company->getId(),
            ]);
        }

        return $this->render('backend/company/new.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="edit", methods={"GET","POST"})
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

            return $this->redirectToRoute('backend_company_index', [
                'id' => $company->getId(),
            ]);
        }

        return $this->render('backend/company/edit.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }
}
