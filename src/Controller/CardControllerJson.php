<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\Card;
//use App\Card\CardGraphic;
//use App\Card\DeckOfCards;
use App\Card\DeckOfJokers;
//use App\Dice\DiceHand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardControllerJson extends AbstractController
{
    #[Route("/api/deck", name: "card_api_deck", methods: ['GET'])]
    public function apiDeck(): Response
    {
        $deck = new DeckOfJokers();
        $cardArray = [];
        foreach ($deck->deck as $card) {
            $suit = $card->getColor();
            $val = $card->getKingdom();
            $cardArray[] = '[' . $suit . $val . ']';
        }
        $data = [
            'deck' => $cardArray
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/deck/shuffle", name: "card_api_shuffle", methods: ['GET'])]
    public function apiShuff(): Response
    {
        return $this->render('card/api_shuffle.html.twig');
    }
    #[Route("/api/deck/shuffle", name: "card_json_shuffle", methods: ['POST'])]
    public function apiShuffle(
        SessionInterface $session
    ): Response {
        $session->clear();
        $deck = new DeckOfJokers();
        //$deck->shuffle_deck();
        $cardArray = [];
        foreach ($deck->deck as $card) {
            $suit = $card->getColor();
            $val = $card->getKingdom();
            $cardArray[] = '[' . $suit . $val . ']';
        }
        shuffle($cardArray);
        $session->set("deckArray", $cardArray);
        $data = [
            'deck' => $cardArray
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/deck/draw", name: "card_api_draw", methods: ['GET'])]
    public function apiDraw(): Response
    {
        return $this->render('card/api_draw.html.twig');
    }
    #[Route("/api/deck/draw", name: "card_json_draw", methods: ['POST'])]
    public function apiDrawCard(
        SessionInterface $session
    ): Response {
        //$removedList = $session->get("cards", []);
        if (null !== $session->get("deckArray")) {
            $cardArray = $session->get("deckArray");
        } else {
            $deck = new DeckOfJokers();
            $cardArray = [];
            foreach ($deck->deck as $card) {
                $suit = $card->getColor();
                $val = $card->getKingdom();
                $cardArray[] = '[' . $suit . $val . ']';
            }
        }
        $amount = count($cardArray) - 1;
        $drawnCard = array_pop($cardArray);
        $session->set("deckArray", $cardArray);
        $data = [
            "deck" => $amount,
            "card" => $drawnCard
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/deck/draw/middle", name: "card_json_middle", methods: ['POST'])]
    public function apiMiddle(
        Request $request
    ): Response {
        $num = $request->request->get('num');
    }
    #[Route("/api/deck/draw/{num<\d+>}", name: "card_json_draw_num", methods: ['POST'])]
    public function apiDrawCards(
        int $num,
        SessionInterface $session
    ): Response {
        //$removedList = $session->get("cards", []);
        if (null !== $session->get("deckArray")) {
            $cardArray = $session->get("deckArray");
        } else {
            $deck = new DeckOfJokers();
            $cardArray = [];
            foreach ($deck->deck as $card) {
                $suit = $card->getColor();
                $val = $card->getKingdom();
                $cardArray[] = '[' . $suit . $val . ']';
            }
        }
        $amount = count($cardArray) - $num;
        $drawnCards = [];
        for ($i = 1; $i <= $num; $i++) {
            $drawnCards[] = array_pop($cardArray);
        }
        //$drawnCard = array_pop($cardArray);
        $session->set("deckArray", $cardArray);
        $data = [
            "deck" => $amount,
            "cards" => $drawnCards
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
