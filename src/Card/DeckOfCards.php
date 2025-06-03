<?php

namespace App\Card;

use App\Card\Card;

/**
 * Class representing a deck of cards.
 */
class DeckOfCards
{
    public $deck = [];

    /**
     * Constructor method.
     */
    public function __construct()
    {
        $suits = ["hearts", "diamonds", "spades", "clubs"];
        $this->deck = [];
        for ($i = 0; $i < 4; $i++) {
            for ($i2 = 0; $i2 < 13; $i2++) {
                $card = new Card();
                $card->setValue($i2, $suits[$i]);
                $this->deck[] = $card;
            }
        }
    }

    /**
     * Method to get shuffle deck.
     */
    public function shuffle(): void
    {
        $deckArray = $this->deck;
        shuffle($deckArray);
        $this->deck = $deckArray;
    }

    /**
     * Method to get sort deck.
     */
    public function sort(): void
    {
        $newDeck = [];
        $hearts = [];
        $diamonds = [];
        $spades = [];
        $clubs = [];
        $joker = [];
        foreach ($this->deck as $card) {
            if ($card->getSuit() == "hearts") {
                $hearts[] = $card;
            }
            if ($card->getSuit() == "diamonds") {
                $diamonds[] = $card;
            }
            if ($card->getSuit() == "spades") {
                $spades[] = $card;
            }
            if ($card->getSuit() == "clubs") {
                $clubs[] = $card;
            }
        }
        $suits = [
            $hearts,
            $diamonds,
            $spades,
            $clubs,
            $joker
        ];
        foreach ($suits as $suit) {
            sort($suit);
            foreach ($suit as $curSuit) {
                $newDeck[] = $curSuit;
            }
        }
        $this->deck = $newDeck;
    }

    /**
     * Method to swap the cards in deck for an array of cards.
     */
    public function swapShuffle($deckArray): void
    {
        $this->deck = $deckArray;
    }

    /**
     * Method to add card to deck.
     */
    public function add(Card $card): void
    {
        $this->deck[] = $card;
    }

    /**
     * Method to remove a card from deck.
     */
    public function remove(string $str): void
    {
        $counter = 0;
        foreach ($this->deck as $card) {
            if ($card->getKingdom() . "-" . $card->getSuit() === $str) {
                array_splice($this->deck, $counter, 1);
            }
            $counter += 1;
            //$suits[] = $card->getSuit();
        }
    }

    /**
     * Method to get the number of cards in deck.
     */
    public function getNumberCards(): int
    {
        return count($this->deck);
    }

    /**
     * Method to return all cards in the deck as an array of strings with suit and value.
     */
    public function returnDeck(): array
    {
        $cardArray = [];
        foreach ($this->deck as $card) {
            $suit = $card->getColor();
            $val = $card->getKingdom();
            $cardArray[] = '[' . $suit . $val . ']';
        }
        return $cardArray;
    }

    /**
     * Method to draw cards from deck.
     */
    public function draw($num): array
    {
        $drawnCards = [];
        for ($i = 1; $i <= $num; $i++) {
            $drawnCards[] = array_pop($this->deck);
        }
        return $drawnCards;
    }
}
