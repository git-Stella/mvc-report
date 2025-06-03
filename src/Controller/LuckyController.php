<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class for early testing for later use in controllers.
 */
class LuckyController
{
    /**
     * Route to show random number.
     */
    #[Route('/lucky/number')]
    public function number(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body><h1>Lucky number: '.$number.'<h1></body></html>'
        );
    }
    /**
     * Route to show greeting.
     */
    #[Route("/lucky/hi")]
    public function hiya(): Response
    {
        return new Response(
            '<html><body>Hi to you!</body></html>'
        );
    }
}
