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
        $deck = new DeckOfCards();
        //add session played later and use that to figure out what is in hand and on field...
        //for later kmom
        if (null !== $session->get("deck")) {
            $session->get("deck");
        } else {
            $session->set("deck", $deck->getNumberCards());
        }
        if (null !== $session->get("hand")) {
            $session->get("hand");
        } else {
            $session->set("hand", 0);
        }
        if (null !== $session->get("cards")) {
            $session->get("cards");
        } else {
            $session->set("cards", []);
        }
        if (null !== $session->get("order")) {
            $session->get("order");
        } else {
            $session->set("order", $deck->getValues());
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
            "hand" => $session->get("hand", "empty"),
            "deck" => $session->get("deck", "empty"),
            "cards" => $session->get("cards", "empty")
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
    ): Response {
        $session->clear();
        $deck = new DeckOfCards();
        //$shuffleArray = array();
        //$toShuffle = $deck->deck;
        $split1 = [];
        $split2 = [];
        //$toShuffle = $deck->getValues();
        //srand();
        $deck->shuffle_deck();
        $shuffled = $deck->getValues();

        $session->set("cards", []);
        $session->set("order", $shuffled);
        foreach ($shuffled as $splitter) {
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
    #[Route("/card/deck/draw", name: "card_draw")]
    public function deck_draw(
        SessionInterface $session
    ): Response {
        $removedList = $session->get("cards", []);
        $deck = new DeckOfCards();
        if (null !== $session->get("order")) {
            $deck->swap_shuffle($session->get("order"));
        }
        if (null !== $session->get("cards")) {
            foreach ($removedList as $card) {
                $deck->remove($card);
            }
        }
        if (1 > $session->get("deck", 0)) {
            throw new \Exception("Can not draw more cards then exist!");
        }
        $cardsLeft = $session->get("deck", 1) - 1;
        $session->set("deck", $cardsLeft);
        //set session after pop
        $toSplit = $deck->getValues();
        $split1 = [];
        $split2 = [];
        //$splitted = [[][]];
        foreach ($toSplit as $splitter) {
            $split = explode("-", $splitter);
            $split1[] = $split[0];
            $split2[] = $split[1];
        }
        $cardVal = array_pop($split1);
        $cardSuit = array_pop($split2);
        $cardRemoved = $cardVal . "-" . $cardSuit;
        $removedList[] = $cardRemoved;
        $session->set("cards", $removedList);
        $data = [
            "numleft" => $cardsLeft,
            "suit" => $cardSuit,
            "val" => $cardVal
        ];
        return $this->render('card/draw.html.twig', $data);
    }
    #[Route("/card/deck/draw/{num<\d+>}", name: "card_draw_num")]
    public function deck_draw_cards(
        int $num,
        SessionInterface $session
    ): Response {
        $removedList = $session->get("cards", []);
        $deck = new DeckOfCards();
        if (null !== $session->get("order")) {
            $deck->swap_shuffle($session->get("order"));
        }
        if (null !== $session->get("cards")) {
            foreach ($removedList as $card) {
                $deck->remove($card);
            }
        }
        if ($num > $session->get("deck", 0)) {
            throw new \Exception("Can not draw more cards then exist!");
        }
        $cardsLeft = $session->get("deck", 1) - $num;
        $session->set("deck", $cardsLeft);
        //set session after pop
        $toSplit = $deck->getValues();
        $split1 = [];
        $split2 = [];
        //$splitted = [[][]];
        foreach ($toSplit as $splitter) {
            $split = explode("-", $splitter);
            $split1[] = $split[0];
            $split2[] = $split[1];
        }
        $cardVal = [];
        $cardSuit = [];
        for ($i = 1; $i <= $num; $i++) {
            $cVal = array_pop($split1);
            $cSuit = array_pop($split2);
            $cardVal[] = $cVal;
            $cardSuit[] = $cSuit;
            $cardRemoved = $cVal . "-" . $cSuit;
            $removedList[] = $cardRemoved;
        }
        $session->set("cards", $removedList);
        $data = [
            "numleft" => $cardsLeft,
            "suits" => $cardSuit,
            "vals" => $cardVal
        ];
        return $this->render('card/draw_many.html.twig', $data);
    }
}
