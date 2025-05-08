<?php

namespace App\Game;

use App\Card\Deck;
use App\Card\Card;

class Player
{
    public $hand;
    //private $hand;
    public function __construct()
    {
        $this->hand = [];
    }
    public function draw($card): string
    {
        $this->hand[] = $card;
        return "drawn";
    }
    public function playHand(): array
    {
        $hand = [];
        foreach ($this->hand as $card) {
            $suit = $card->getColor();
            $val = $card->getKingdom();
            $hand[] = '[' . $suit . $val . ']';
        }
        $this->hand = [];
        return $hand;
    }
    public function calcPoints(): array
    {
        //$point_array = [];
        //might redo and do simpler foreach with a foreach point_array..
        $points1 = 0;
        $points2 = 0;
        $points3 = 0;
        foreach ($this->hand as $card) {
            $val = $card->getValue() + 1;
            if ($card->getValue() == 13) {
                $val = 0;
            }
            $points1 += $val;
        }
        foreach ($this->hand as $card) {
            $val = $card->getValue() + 1;
            if ($card->getValue() == 0) {
                $val = 14;
            }/* else {
                $val = $card->get_value() + 1;
            }*/
            if ($card->getValue() == 13) {
                $val = 0;
            }
            $points2 += $val;
        }
        foreach ($this->hand as $card) {
            $val = $card->getValue() + 1;
            if ($card->getValue() == 13) {
                $val = 15;
            }/* else {
                $val = $card->get_value() + 1;
            }*/
            $points3 += $val;
        }
        $pointArray = [$points1, $points2, $points3];
        return $pointArray;
    }
    public function drawCard($deck): void
    {
        $card = array_pop($deck->deck);
        $this->draw($card);
    }
}
