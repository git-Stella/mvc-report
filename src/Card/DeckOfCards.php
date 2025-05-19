<?php

namespace App\Card;

use App\Card\Card;

class DeckOfCards
{
    public $deck = [];

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

    public function shuffle(): void
    {
        $deckArray = $this->deck;
        //print($deckArray[0]);
        shuffle($deckArray);
        //shuffle($this->deck);
        $this->deck = $deckArray;
    }

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
        sort($hearts);
        sort($diamonds);
        sort($spades);
        sort($clubs);
        $suits = [
            $hearts,
            $diamonds,
            $spades,
            $clubs,
            $joker
        ];
        foreach ($suits as $suit) {
            foreach ($suit as $curSuit) {
                $newDeck[] = $curSuit;
            }
        }
        $this->deck = $newDeck;
    }

    public function swapShuffle($deckArray): void
    {
        $this->deck = $deckArray;
    }

    public function add(Card $card): void
    {
        $this->deck[] = $card;
    }

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

    public function getNumberCards(): int
    {
        return count($this->deck);
    }

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

    /*public function getValues(): array
    {
        //$values = [];
        //$suits = [];
        $returner = [];
        foreach ($this->deck as $card) {
            $returner[] = $card->getKingdom() . "-" . $card->getSuit();
            //$suits[] = $card->getSuit();
        }
        //$returner = [$values, $suits];
        return $returner;
    }

    public function getString(): array
    {
        $values = [];
        foreach ($this->deck as $card) {
            $values[] = $card->getAsString();
        }
        return $values;
    }*/

    public function draw($num): array
    {
        $drawnCards = [];
        for ($i = 1; $i <= $num; $i++) {
            $drawnCards[] = array_pop($this->deck);
        }
        return $drawnCards;
    }
}
