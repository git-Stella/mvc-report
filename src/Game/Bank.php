<?php

namespace App\Game;

use App\Card\Deck;
use App\Card\Card;

class Bank extends Player
{
    //private $hand;
    public function __construct()
    {
        parent::__construct();
    }
    public function draw($card): string
    {
        //do something with calc_points to check how valuable hand is
        //then chance based if it draws more based on hand total points
        $pointsArray = parent::calcPoints();
        $filteredArray = [];
        foreach ($pointsArray as $point) {
            if ($point <= 21) {
                $filteredArray[] = $point;
            }
        }
        $filteredArray[] = -10;
        //https://www.w3schools.com/php/func_math_max.asp
        $currentPoints = max($filteredArray);
        if ($currentPoints < 0) {
            return "stop";
        }
        if ($currentPoints < 17) {
            $this->hand[] = $card;
            return "keep going";
        }
        if ($currentPoints > 18) {
            return "stop";
        }
        $random = rand(0, 100);
        if ($random > 90) {
            $this->hand[] = $card;
            return "stop";
        }
        return "stop";
        //$this->hand[] = $card;
    }
    public function drawCards($deck, $num = 1): void
    {
        $keepGoing = "keep going";
        for ($i = 0; $i < $num; $i++) {
            if ($keepGoing == "keep going") {
                $card = array_pop($deck->deck);
                $keepGoing = $this->draw($card);
            }
        }
    }
}
