<?php

namespace App\Poker;

use App\Card\DeckOfCards;
use App\Card\Card;
use App\Poker\Rules;

/**
 * Class representing poker players.
 */
class Players
{
    public $playerScore;
    public $computerScore;
    public $pHand;
    public $cHand;
    public $pBet;
    public $cBet;
    public $pFunds;
    public $cFunds;
    public $round;
    public $rules;
    /**
     * Constructor method
     */
    public function __construct()
    {
        $this->playerScore = 0;
        $this->computerScore = 0;
        $this->pHand = [];
        $this->cHand = [];
        $this->pBet = 0;
        $this->cBet = 0;
        $this->pFunds = 5000;
        $this->cFunds = 5000;
        $this->round = 0;
        $this->rules = new Rules();
    }
    /**
     * Method to figure out what cards to drop
     */
    public function handLogic($deck)
    {
        //think it is more important it aims for flush or pair/four of a kind/full house
        $suits = [];
        $values = [];
        foreach ($this->cHand as $card) {
            $suits[] = $card->suit;
            $values[] = $card->value;
        }
        $suitCounts = array_count_values($suits);
        $valueCounts = array_count_values($values);
        $maxSuit = max($suitCounts);
        $maxVal = max($valueCounts);
        $suitKey = array_search($maxSuit, $suitCounts);
        $valKey = array_search($maxVal, $valueCounts);
        if ($maxSuit < $maxVal) {
            $deck->deck = $this->valLogic($this->cHand, $valKey, $deck);
        }
        $deck->deck = $this->suitLogic($this->cHand, $suitKey, $deck);
        return $deck;
    }
    /**
     * Method to handle the logic if computer has lots of cards of the same suit.
     */
    public function suitLogic($hand, $suit, $deck)
    {
        //$toDiscard = [];
        $newHand = [];
        foreach ($hand as $card) {
            if ($card->suit == $suit) {
                $newHand[] = $card;
            }
        }
        $toDraw = 5 - count($newHand);
        if ($toDraw > 0) {
            $drawnCards = $deck->draw($toDraw);
            foreach ($drawnCards as $card) {
                $newHand[] = $card;
            }
        }
        $this->cHand = $newHand;
        return $deck;
    }
    /**
     * Method to handle the logic if computer has lots of cards of same value.
     */
    public function valLogic($hand, $val, $deck)
    {
        $newHand = [];
        $checkHand = [];
        $valCheck = [];
        //gonna need this one to be more complex to make it able to aim for both two pair and full house
        foreach ($hand as $card) {
            if ($card->value == $val) {
                $newHand[] = $card;
            }
            if ($card->value != $val) {
                $checkHand[] = $card;
                //check these to see if one comes up twice
                $valCheck[] = $card->value;
            }
        }
        $valueCounts = array_count_values($valCheck);
        $maxVal = max($valueCounts);
        //this is just to prevent it from being relevant unless the right conditions:
        $valKey = 3000;
        if ($maxVal > 1) {
            $valKey = array_search($maxVal, $valueCounts);
        }
        foreach ($checkHand as $rCard) {
            if ($rCard->value == $valKey) {
                $newHand[] = $rCard;
            }
        }
        $toDraw = 5 - count($newHand);
        if ($toDraw > 0) {
            $drawnCards = $deck->draw($toDraw);
            foreach ($drawnCards as $card) {
                $newHand[] = $card;
            }
        }
        //$drawnCards = $deck->draw($toDraw);
        $this->cHand = $newHand;
        return $deck;
    }
    /**
     * Method to decide if the computer will raise bet.
     */
    public function bet($num)
    {
        //need to somehow check their budgets...
        $this->pBet = $num;
        $rules = $this->rules;
        $difference = $this->pBet - $this->cBet;
        $message = "";
        if ($difference < 50) {
            $this->cBet = $num + 50;
            $message = "raise";
        }
        if ($difference <= 500) {
            $this->cBet = $num;
            $message = "call";
        }
        //maybe change this to be random if fold or call?
        if ($difference > 500) {
            $message = "fold";
        }
        if ($this->pBet > $this->pFunds) {
            $this->pBet = $this->pFunds;
            $message = "all in";
        }
        if ($this->cBet > $this->cFunds) {
            $this->cBet = $this->cFunds;
            $message = "all in";
        }
        return $message;
    }
    /**
     * Method to determine the winner of a round.
     */
    public function score($rules)
    {
        $playerHand = $rules->handPoints($this->pHand);
        $computerHand = $rules->handPoints($this->cHand);
        $dict = $rules->dict;
        $pCount = 0;
        $cCount = 0;
        foreach ($dict as $rule) {
            if ($playerHand == $rule) {
                break;
            }
            $pCount += 1;
        }
        foreach ($dict as $rule) {
            if ($computerHand == $rule) {
                break;
            }
            $cCount += 1;
        }
        if ($pCount < $cCount) {
            $this->playerScore += 1;
            return "playerWin";
        }
        if ($cCount < $pCount) {
            $this->computerScore += 1;
            return "computerWin";
        }
        return "tie";
    }
    //must check if they got a tie which has higher value...
    /**
     * Bla bla
     */
    public function scoring()
    {
        $result = $this->score($this->rules);
        if ($result == "playerWin") {
            return "Player won!";
        }
        if ($result == "computerWin") {
            return "Computer won!";
        }
        //check which hand has higher value card or higher value suit
        //give win to them
        //but if it pair I will need to check if the pair is higher
    }
}
