<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\Card;
//use App\Card\CardGraphic;
use App\Card\DeckOfCards;
use App\Card\DeckOfJokers;
//use App\Dice\DiceHand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

//put version of deck with jokers in json api...
class CardGameController extends AbstractController
{
    #[Route("/game/card", name: "card_start")]
    public function home(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfJokers();
        //add session played later and use that to figure out what is in hand and on field...
        //for later kmom
        if (null !== $session->get("deck")) {
            $session->get("deck");
        } else {
            $session->set("deck", 54);
        }
        if (null !== $session->get("deckArray")) {
            $session->get("deckArray");
        } else {
            $cardArray = [];
            foreach ($deck->deck as $card) {
                $suit = $card->getColor();
                $val = $card->getKingdom();
                $cardArray[] = '[' . $suit . $val . ']';
            }
            $session->set("deckArray", $cardArray);
        }
        return $this->render('card/home.html.twig');
    }
    #[Route("/session", name: "card_session")]
    public function card_session(
        SessionInterface $session
    ): Response {
        //I need removed cards and cards left...
        //I need the current deck order too...
        $data = [
            "decklist" => $session->get("deckArray", ["empty"]),
            "deck" => $session->get("deck", 0)
            //"decktest2" => $session->get("decktest2", "empty")
        ];
        return $this->render('card/session_debug.html.twig', $data);
    }
    #[Route("/session/delete", name: "card_reset")]
    public function card_reset(
        SessionInterface $session
    ): Response {
        $session->clear();
        $this->addFlash(
            'notice',
            'Session has been cleared.'
        );
        return $this->redirectToRoute('card_start');
    }
    #[Route("/card/deck", name: "card_deck")]
    public function deck(
        //SessionInterface $session
    ): Response {
        $deck = new DeckOfJokers();
        //$toSplit = $deck->getValues();
        //$split1 = [];
        //$split2 = [];
        //$splitted = [[][]];
        $cardArray = [];
        foreach ($deck->deck as $card) {
            $suit = $card->getColor();
            $val = $card->getKingdom();
            $cardArray[] = '[' . $suit . $val . ']';
        }
        /*foreach ($toSplit as $splitter) {
            $split = explode("-", $splitter);
            $split1[] = $split[0];
            $split2[] = $split[1];
        }
        //$splitted = explode($toSplit)
        $vals = $split1;
        $suits = $split2;
        $data = [
            "suits" => $suits,
            "vals" => $vals
        ];*/
        $data = [
            "deck" => $cardArray,
            "num" => 54
        ];
        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "card_shuffle")]
    public function deck_shuffle(
        SessionInterface $session
    ): Response {
        $session->clear();
        $deck = new DeckOfJokers();
        //$shuffleArray = array();
        //$toShuffle = $deck->deck;
        //$split1 = [];
        //$split2 = [];
        //$toShuffle = $deck->getValues();
        //srand();
        $cardArray = [];
        foreach ($deck->deck as $card) {
            $suit = $card->getColor();
            $val = $card->getKingdom();
            $cardArray[] = '[' . $suit . $val . ']';
        }
        shuffle($cardArray);
        //$shuffled = $deck->getValues();

        //$session->set("cards", []);
        //$session->set("order", $shuffled);
        /*foreach ($shuffled as $splitter) {
            $split = explode("-", $splitter);
            $split1[] = $split[0];
            $split2[] = $split[1];
        }
        $vals = $split1;
        $suits = $split2;
        $data = [
            "suits" => $suits,
            "vals" => $vals
        ];*/
        $session->set("deckArray", $cardArray);
        $session->set("deck", 54);
        $data = [
            "deck" => $cardArray,
            "num" => $session->get("deck", 0)
        ];
        return $this->render('card/deck.html.twig', $data);
    }
    #[Route("/card/deck/draw", name: "card_draw")]
    public function deck_draw(
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
        $session->set("deck", $amount);
        $data = [
            "deck" => $amount,
            "card" => $drawnCard
        ];
        //$cardVal = array_pop($split1);
        //$cardSuit = array_pop($split2);
        //$cardRemoved = $cardVal . "-" . $cardSuit;
        //$removedList[] = $cardRemoved;
        //$session->set("cards", $removedList);
        return $this->render('card/draw.html.twig', $data);
    }
    #[Route("/card/deck/draw/{num<\d+>}", name: "card_draw_num")]
    public function deck_draw_cards(
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
        $session->set("deck", $amount);
        $data = [
            "deck" => $amount,
            "cards" => $drawnCards
        ];
        return $this->render('card/draw_many.html.twig', $data);
    }
}
