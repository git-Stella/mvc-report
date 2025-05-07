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
    public function drawCard($card): string
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
        //https://www.w3schools.com/php/func_math_max.asp
        $currentPoints = max($filteredArray);
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
}
