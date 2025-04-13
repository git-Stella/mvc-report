<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\Card;
use App\Card\CardGraphic;
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
    ): Response
    {
        //do some kind of for loop with each suit to add all to the deck
        $deck = new DeckOfCards();
        $card = new Card();
        $card->setValue(7, "diamonds");
        $val = $card->getValue();
        $suit = $card->getSuit();
        $session->set("decktest1", $deck->getNumberCards());
        $session->set("decktest2", $deck->getString());
        $session->set("testcard", $card->getCard());
        $session->set("val", $val);
        $session->set("suit", $suit);
        $session->set("color", $card->getColor());
        $session->set("hand", "hand");
        return $this->render('card/home.html.twig');
    }
    #[Route("/session", name: "card_session")]
    public function card_session(
        SessionInterface $session
    ): Response
    {
        $val = $session->get("val");
        $suit = $session->get("suit");
        $card = new Card();
        $card->setValue($val, $suit);
        $data = [
            "hand" => $session->get("hand", "empty"),
            "testcard" => $session->get("testcard", "empty"),
            "val" => $session->get("val", "empty"),
            "suit" => $session->get("suit", "empty"),
            "color" => $session->get("color", "empty"),
            "decktest1" => $session->get("decktest1", "empty"),
            //"decktest2" => $session->get("decktest2", "empty")
        ];
        return $this->render('card/session_debug.html.twig', $data);
    }
    #[Route("/session/delete", name: "card_reset")]
    public function card_reset(
        SessionInterface $session
    ): Response
    {
        $session->clear();
        $data = [
            "hand" => $session->get("hand", "empty"),
            "testcard" => $session->get("testcard", "empty"),
            "val" => $session->get("val", "empty"),
            "suit" => $session->get("suit", "empty"),
            "color" => $session->get("color", "empty"),
            "decktest1" => $session->get("decktest1", "empty"),
            //"decktest2" => $session->get("decktest2", "empty")
        ];
        return $this->render('card/session_debug.html.twig', $data);
    }
    #[Route("/card/deck", name: "card_deck")]
    public function deck(
        SessionInterface $session
    ): Response
    {
        $deck = new DeckOfCards();
        $toSplit = $deck->getValues();
        $split1 = [];
        $split2 = [];
        //$splitted = [[][]];
        foreach ($toSplit as $splitter) {
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
        ];
        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "card_shuffle")]
    public function deck_shuffle(
        SessionInterface $session
    ): Response
    {
        $deck = new DeckOfCards();
        $shuffleArray = array();
        $toShuffle = $deck->getValues();
        $split1 = [];
        $split2 = [];
        //$toShuffle = $deck->getValues();
        //srand();
        shuffle($toShuffle);
        foreach ($toShuffle as $splitter) {
            $split = explode("-", $splitter);
            $split1[] = $split[0];
            $split2[] = $split[1];
        }
        $vals = $split1;
        $suits = $split2;
        $data = [
            "suits" => $suits,
            "vals" => $vals
        ];
        return $this->render('card/deck.html.twig', $data);
    }
}