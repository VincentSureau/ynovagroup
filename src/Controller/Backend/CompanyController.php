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
 * @Route("/company", name="backend_")
 */
class CompanyController extends AbstractController
{
    /**
     * @Route("/", name="company_index", methods={"GET"})
     */
    public function index(CompanyRepository $companyRepository): Response
    {
        return $this->render('backend/company/index.html.twig', [
            'companys' => $companyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="company_show", methods={"GET"})
     */
    public function show(Company $company): Response
    {
        return $this->render('backend/company/show.html.twig', [
            'company' => $company,
        ]);
    }
    
    /**
     * @Route("/{id}/edit", name="company_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Company $company): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {

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
