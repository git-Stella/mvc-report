<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ControllerJson
{
    #[Route("/api/lucky/number")]
    public function jsonNumber(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'lucky-number' => $number,
            'lucky-message' => 'Hi there!',
        ];

        // return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/quote", name: "quote")]
    public function quote(): Response
    {
        $number = random_int(0, 2);
        date_default_timezone_set("Europe/Stockholm");

        $val1 = "Shovel down your bland rations. Drink your coffee flavored sludge. It sucks but that is being human. - O´Keefe";
        $val2 = ".... - Link";
        $val3 = "I miss my wife Tails. I miss her a lot. - Eggman (real!)";

        $quoteArray = array($val1, $val2, $val3);
        $quote = $quoteArray[$number];
        $data = [
            'quote' => $quote,
            'date' => date("Y-m-d"),
            'time' => date("H:i:s")
        ];

        //return $this->render('lucky_number.html.twig', $data);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
