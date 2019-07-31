<?php
namespace App\Controller\Frontend;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
class SitemapController extends AbstractController
{
    /**
     * @Route(
     *      "/sitemap.{_format}", name="sitemap",
     *      requirements={"_format": "xml"}
     * )
     */
    public function index()
    {
        return $this->render('home/sitemap.html.twig');
    }
}