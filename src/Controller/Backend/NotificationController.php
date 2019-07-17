<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    /**
     * @Route("/notifications", name="notification")
     */
    public function index()
    {
        return $this->render('backend/notification/index.html.twig', [
            'current' => 'notification',
        ]);
    }
}
