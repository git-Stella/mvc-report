<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ControllerJson
{
    #[Route("/api")]
    public function apiLanding(): Response
    {
        $data = [
            '/api/lucky/number' => 'Shows a random number between 0 and 100 
            https://www.student.bth.se/~anka21/dbwebb-kurser/mvc/me/report/public/api/lucky/number',
            '/api/quote' => 'Shows one of three random quotes and the date and time for when it was generated 
            https://www.student.bth.se/~anka21/dbwebb-kurser/mvc/me/report/public/api/quote',
            '/api/deck' => 'Shows all cards in deck in order 
            https://www.student.bth.se/~anka21/dbwebb-kurser/mvc/me/report/public/api/deck',
            '/api/deck/shuffle' => 'Shuffles deck and stores in session 
            https://www.student.bth.se/~anka21/dbwebb-kurser/mvc/me/report/public/api/deck/shuffle',
            '/api/deck/draw' => 'Draw one or more cards, deck is stored in session 
            https://www.student.bth.se/~anka21/dbwebb-kurser/mvc/me/report/public/api/deck/draw',
            'Hey' => 'https://www.student.bth.se/~anka21/dbwebb-kurser/mvc/me/report/public'
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/lucky/number", name: "api_luck_num")]
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
        $number = random_int(0, 3);
        date_default_timezone_set("Europe/Stockholm");

        $val1 = "Shovel down your bland rations. Drink your coffee flavored sludge. It sucks but that is being human. - O´Keefe";
        $val2 = ".... - Link";
        $val3 = "I miss my wife Tails. I miss her a lot. - Eggman (real!)";
        $val4 = "Grow fat from strength. - Emperor Calus";

        $quoteArray = array($val1, $val2, $val3, $val4);
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
