<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\Card;
use App\Card\DeckOfJokers;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Controller class for card game routes
 */
class CardGameController extends AbstractController
{
    /**
     * Route for landing page for the card pages.
     */
    #[Route("/game/card", name: "card_start")]
    public function home(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfJokers();
        if (null === $session->get("deck")) {
            $session->set("deck", count($deck->deck));
        }
        if (null === $session->get("deckArray")) {
            $session->set("deckArray", $deck->deck);
        }
        return $this->render('card/home.html.twig');
    }
    /**
     * Route to show what is currently stored in the session regarding deck and cards.
     */
    #[Route("/session", name: "card_session")]
    public function cardSession(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfJokers();
        $placehold = $session->get("deckArray", $deck->deck);
        $deck->swapShuffle($placehold);
        $cardArray = [];
        foreach ($deck->deck as $card) {
            $suit = $card->getColor();
            $val = $card->getKingdom();
            $cardArray[] = '[' . $suit . $val . ']';
        }
        $data = [
            "decklist" => $cardArray,
            "deck" => $session->get("deck", 0)
        ];
        return $this->render('card/session_debug.html.twig', $data);
    }
    /**
     * Route to clear session
     */
    #[Route("/session/delete", name: "card_reset")]
    public function cardReset(
        SessionInterface $session
    ): Response {
        $session->clear();
        $this->addFlash(
            'notice',
            'Session has been cleared.'
        );
        return $this->redirectToRoute('card_start');
    }
    /**
     * Route for showing the deck sorted and how many cards are left in it.
     */
    #[Route("/card/deck", name: "card_deck")]
    public function deck(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfJokers();
        $placehold = $session->get("deckArray", $deck->deck);
        $deck->swapShuffle($placehold);
        $deck->sort();
        $cardArray = [];
        foreach ($deck->deck as $card) {
            $suit = $card->getColor();
            $val = $card->getKingdom();
            $cardArray[] = '[' . $suit . $val . ']';
        }
        $data = [
            "deck" => $cardArray,
            "num" => count($cardArray)
        ];
        return $this->render('card/deck.html.twig', $data);
    }
    /**
     * Route to shuffle the deck
     */
    #[Route("/card/deck/shuffle", name: "card_shuffle")]
    public function deckShuffle(
        SessionInterface $session
    ): Response {
        $session->clear();
        $deck = new DeckOfJokers();
        $deck->shuffle();
        $cardArray = [];
        foreach ($deck->deck as $card) {
            $suit = $card->getColor();
            $val = $card->getKingdom();
            $cardArray[] = '[' . $suit . $val . ']';
        }
        $session->set("deckArray", $deck->deck);
        $session->set("deck", count($cardArray));
        $data = [
            "deck" => $cardArray,
            "num" => $session->get("deck", 0)
        ];
        return $this->render('card/deck.html.twig', $data);
    }
    /**
     * Route to draw card from deck
     */
    #[Route("/card/deck/draw", name: "card_draw")]
    public function deckDraw(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfJokers();
        $placehold = $session->get("deckArray", $deck->deck);
        $deck->swapShuffle($placehold);
        $cardArray = [];
        foreach ($deck->deck as $card) {
            $suit = $card->getColor();
            $val = $card->getKingdom();
            $cardArray[] = '[' . $suit . $val . ']';
        }
        $amount = count($cardArray) - 1;
        $drawnCard = array_pop($cardArray);
        array_pop($deck->deck);
        $session->set("deckArray", $deck->deck);
        $session->set("deck", $amount);
        $data = [
            "deck" => $amount,
            "card" => $drawnCard
        ];
        return $this->render('card/draw.html.twig', $data);
    }
    /**
     * Route to draw certain number of cards from deck
     */
    #[Route("/card/deck/draw/{num<\d+>}", name: "card_draw_num")]
    public function deckDrawCards(
        int $num,
        SessionInterface $session
    ): Response {
        $deck = new DeckOfJokers();
        $placehold = $session->get("deckArray", $deck->deck);
        $deck->swapShuffle($placehold);
        $cardArray = [];
        foreach ($deck->deck as $card) {
            $suit = $card->getColor();
            $val = $card->getKingdom();
            $cardArray[] = '[' . $suit . $val . ']';
        }
        $amount = count($cardArray) - $num;
        $drawnCards = [];
        for ($i = 1; $i <= $num; $i++) {
            $drawnCards[] = array_pop($cardArray);
            array_pop($deck->deck);
        }
        $session->set("deckArray", $deck->deck);
        $session->set("deck", $amount);
        $data = [
            "deck" => $amount,
            "cards" => $drawnCards
        ];
        return $this->render('card/draw_many.html.twig', $data);
    }
}
