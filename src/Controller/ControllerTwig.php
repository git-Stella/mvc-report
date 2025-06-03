<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller class for standard twig using routes.
 */
class ControllerTwig extends AbstractController
{
    /**
     * Shows a random number.
     */
    #[Route("/lucky/number/twig", name: "lucky_number")]
    public function number(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'number' => $number
        ];

        return $this->render('lucky_number.html.twig', $data);
    }
    /**
     * Shows a random number and random quote from a few possibilities.
     */
    #[Route("/lucky", name: "lucky")]
    public function lucky(): Response
    {
        $number = random_int(0, 100);
        $number2 = random_int(0, 2);

        $val1 = "Shovel down your bland rations. Drink your coffee flavored sludge. It sucks but that is being human. - O´Keefe";
        $val2 = ".... - Link";
        $val3 = "I miss my wife Tails. I miss her a lot. - Eggman (real!)";

        $quoteArray = array($val1, $val2, $val3);
        $quote = $quoteArray[$number2];

        $data = [
            'number' => $number,
            'quote' => $quote
        ];

        return $this->render('lucky_number.html.twig', $data);
    }
    /**
     * Home page.
     */
    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }
    /**
     * Route for about page.
     */
    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }
    /**
     * Report page detailing analysis of each kmom.
     */
    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }
    /**
     * Analysis page for the metrics in the project.
     */
    #[Route("/metrics", name: "metrics")]
    public function metrics(): Response
    {
        return $this->render('metrics.html.twig');
    }
}
