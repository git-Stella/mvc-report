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
        $this->dict = [
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
    /**
     * Method to decide which points method to use
     */
    public function handPoints($cards)
    {
        //rework this a bunch...
        //foreach dict instead?
        //make a function that picks based on number which function used
        $counter = 0;
        foreach ($this->dict as $rule) {
            if ($this->rulesWheel($counter, $cards)) {
                return $rule;
            }
            $counter += 1;
        }
        return "error";
    }

    public function rulesWheel($num, $hand)
    {
        $dict = [
            $this->royalFlush($hand),
            $this->straightFlush($hand),
            $this->fourOfAKind($hand),
            $this->fullHouse($hand),
            $this->flush($hand),
            $this->straight($hand),
            $this->threeOfAKind($hand),
            $this->twoPair($hand),
            $this->onePair($hand),
            $this->highCard($hand)
        ];
        return $dict[$num];
    }

    /**
     * Method to check if hand is a royal flush.
     */
    public function royalFlush($hand)
    {
        $suit = $hand[0]->suit;
        foreach ($hand as $card) {
            if ($card->suit !== $suit) {
                return false;
            }
        }
        $valList = [];
        $compareList = [0, 9, 10, 11, 12];
        foreach ($hand as $card) {
            $valList[] = $card->value;
        }
        sort($valList);
        if ($valList == $compareList) {
            return true;
        }
        return false;
    }

    /**
     * Method to check if hand is a straight flush.
     */
    public function straightFlush($hand)
    {
        $suit = $hand[0]->suit;
        foreach ($hand as $card) {
            if ($card->suit !== $suit) {
                return false;
            }
        }
        $valList = [];
        foreach ($hand as $card) {
            $valList[] = $card->value;
        }
        sort($valList);
        if ($valList[0] = $valList[4] - 4) {
            return true;
        }
        return false;
    }

    /**
     * Method to check if hand is four of a kind.
     */
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
            if ($res == 4) {
                return true;
            }
        }
        return false;
    }

    /**
     * Method to check if hand is full house.
     */
    public function fullHouse($hand)
    {
        //can use array count value?
        //make sure it gets exactly 2 values then
        $valList = [];
        foreach ($hand as $card) {
            $valList[] = $card->value;
        }
        $res = array_count_values($valList);
        if (count($res) == 2) {
            return true;
        }
        return false;
    }

    /**
     * Method to check if hand is a flush.
     */
    public function flush($hand)
    {
        //can reuse some stuff from straight flush...
        $suit = $hand[0]->suit;
        foreach ($hand as $card) {
            if ($card->suit !== $suit) {
                return false;
            }
        }
        return true;
    }

    /**
     * Method to check if hand is a straight.
     */
    public function straight($hand)
    {
        //need to rework this...
        $valList = [];
        foreach ($hand as $card) {
            $valList[] = $card->value;
        }
        sort($valList);
        if ($valList[0] !== 0) {
            //need to rework this one...
            $val1 = $valList[0];
            $val2 = $valList[4];
            if ($val1 == $val2 - 4) {
                return true;
            }
            //return false;
        }
        //need to make it work if one is an ace...
        $compareList = [0, 9, 10, 11, 12];
        if ($valList == $compareList) {
            return true;
        }
        return false;
    }

    /**
     * Method to check if hand is three of a kind.
     */
    public function threeOfAKind($hand)
    {
        $counter = 0;
        $valList = [];
        //https://www.w3schools.com/Php/func_array_count_values.asp
        foreach ($hand as $card) {
            $valList[] = $card->value;
        }
        $res = array_count_values($valList);
        foreach ($res as $res) {
            if ($res == 3) {
                return true;
            }
        }
        return false;
    }

    /**
     * Method to check if hand is two pair.
     */
    public function twoPair($hand)
    {
        $counter = 0;
        $valList = [];
        foreach ($hand as $card) {
            $valList[] = $card->value;
        }
        $res = array_count_values($valList);
        foreach ($res as $count) {
            if ($count == 2) {
                $counter += 1;
            }
        }
        if ($counter == 2) {
            return true;
        }
        return false;
    }

    /**
     * Method to check if hand is a one pair.
     */
    public function onePair($hand)
    {
        $valList = [];
        foreach ($hand as $card) {
            $valList[] = $card->value;
        }
        $res = array_count_values($valList);
        foreach ($res as $count) {
            if ($count == 2) {
                return true;
            }
        }
        return false;
    }

    /**
     * Method to use if the other hands are not met.
     */
    public function highCard($hand)
    {
        return true;
    }
}
