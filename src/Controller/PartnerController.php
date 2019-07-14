<?php

namespace App\Controller;

use App\Repository\PartnerRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PartnerController extends AbstractController
{
    /**
     * @Route("/partenaires", name="partenaires")
     */
    public function index(PartnerRepository $partnerRepository)
    {
        $partners = $partnerRepository->findBy(
            ['isActive' => true],
            ['createdAt' => 'DESC']
        );

        return $this->render('partner/index.html.twig', [
            'current' => 'partenaires',
            'partners' => $partners
        ]);
    }
}
