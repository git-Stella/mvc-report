<?php

namespace App\Card;

/**
 * Class representing a deck of cards with jokers. Extends DeckOfCards.
 */
class DeckOfJokers extends DeckOfCards
{
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
        $joker1 = new Card();
        $joker2 = new Card();
        $joker1->setValue(13, "joker");
        $joker2->setValue(13, "joker");
        $this->deck[] = $joker1;
        $this->deck[] = $joker2;
    }
    /**
     * Method to sort deck.
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
            if ($card->getSuit() == "joker") {
                $joker[] = $card;
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
}
