<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller class for standard twig using routes.
 */
class ProjectController extends AbstractController
{
    /**
     * Home page.
     */
    #[Route("/proj", name: "proj")]
    public function projHome(): Response
    {
        return $this->render('proj/home.html.twig');
    }
    #[Route("/proj/about", name: "projabout")]
    public function projAbout(): Response
    {
        return $this->render('proj/about.html.twig');
    }
}
