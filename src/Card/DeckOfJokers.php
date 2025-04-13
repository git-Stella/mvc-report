<?php

namespace App\Card;

class DeckOfJokers extends DeckOfCards
{
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
}
