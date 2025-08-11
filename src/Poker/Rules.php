<?php

namespace App\Poker;

//use App\Card\DeckOfJokers;
use App\Card\Card;

/**
 * Class representing poker rules.
 */
class Rules
{
    public $dict;
    public $points;
    /**
     * Constructor method
     */
    public function __construct()
    {
        //??
        $this->$dict = [
            "Royal Flush",
            "Straight Flush",
            "Four of a Kind",
            "Full House",
            "Flush",
            "Straight",
            "Three of a Kind",
            "Two Pair",
            "One Pair",
            "High Card"
        ];
        $this->points = 0;
    }
    //need function for deciding which other method to calc points with...
    /**
     * Method to decide which points method to use
     */
    public function hand($cards)
    {
        //rework this a bunch...
        //foreach dict instead?
        $handVal = [];
        foreach ($cards as $card) {
            //check values to determine which hand it fits?
            $handVal[] = $card->value . "-" . $card->color;
        }
        sort($handVal);

    }
    //make a function to calc if the hand fits each of the dict hands...

    public function fourOfAKind($hand)
    {
        $counter = 0;
        $valList = [];
        //https://www.w3schools.com/Php/func_array_count_values.asp
        foreach ($hand as $card) {
            $valList[] = $card->value;
        }
        $res = array_count_values($valList);
        foreach ($res as $res) {
            if ($res >= 4) {
                return true;
            }
        }
        return false;
    }
}
