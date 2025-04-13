<?php
namespace App\Card;

use App\Card\Card;

class DeckOfCards
{
    private $deck = [];

    public function __construct()
    {
        $suits = ["hearts", "diamonds", "spades", "clubs"];
        $this->deck = [];
        for ($i = 0; $i < 4; $i++) {
            for ($i2 = 0; $i2 < 13; $i2++) {
                $card = new Card;
                $card->setValue($i2, $suits[$i]);
                $this->deck[] = $card;
            }
        }
    }

    public function add(Card $card): void
    {
        $this->deck[] = $card;
    }

    public function getNumberCards(): int
    {
        return count($this->deck);
    }

    public function getValues(): array
    {
        //$values = [];
        //$suits = [];
        $returner = [];
        foreach ($this->deck as $card) {
            //these are two different arrays rn... not key value pair
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
    }
}