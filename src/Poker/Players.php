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
        if ($maxVal < $maxSuit) {
            $deck->deck = $this->suitLogic($this->cHand, $suitKey, $deck);
        }
        //$deck->deck = $this->suitLogic($this->cHand, $suitKey, $deck);
        return $deck->deck;
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
        return $deck->deck;
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
        return $deck->deck;
    }
    /**
     * Method to decide if the computer will raise bet.
     */
    public function bet($num)
    {
        $this->pBet = $num;
        $rules = $this->rules;
        $difference = $this->pBet - $this->cBet;
        $message = "";
        if ($difference < 50) {
            $this->cBet = $num + 50;
            $message = "raise";
        }
        if ($difference >= 50) {
            $this->cBet = $num;
            $message = "call";
        }
        //maybe change this to be random if fold or call?
        if ($difference > 500) {
            $message = "fold";
        }
        if ($this->pBet >= $this->pFunds) {
            $this->pBet = $this->pFunds;
            $message = "all in";
        }
        if ($this->cBet >= $this->cFunds) {
            $this->cBet = $this->cFunds;
            $message = "all in";
        }
        return $message;
    }
    /**
     * Method to determine the winner of a round, used inside clash.
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
     * Method to decide who wins.
     */
    public function clash()
    {
        $result = $this->score($this->rules);
        $this->round += 1;
        if ($result == "playerWin") {
            return "Player won!";
        }
        if ($result == "computerWin") {
            return "Computer won!";
        }
        return $this->resolveEqual();
    }
    /**
     * Method to resolve same hands.
     */
    public function resolveEqual()
    {
        //check which hand has higher value card or higher value suit
        //give win to them
        //but if it pair I will need to check if the pair is higher
        $pHand = $this->rules->handPoints($this->pHand);
        $cHand = $this->rules->handPoints($this->cHand);
        //test four and three of a kind and pair
        $pVals = [];
        $cVals = [];
        $pSuits = [];
        $cSuits = [];
        for ($i = 0; $i < 5; $i++) {
            if ($this->pHand[$i]->value == 0) {
                $this->pHand[$i]->value = 13;
            }
            if ($this->cHand[$i]->value == 0) {
                $this->cHand[$i]->value = 13;
            }
            $pVals[] = $this->pHand[$i]->value;
            $cVals[] = $this->cHand[$i]->value;
            $pSuits[] = $this->pHand[$i]->suit;
            $cSuits[] = $this->cHand[$i]->suit;
        }
        $unsortedVals = $pVals;
        rsort($pVals);
        rsort($cVals);
        $pMax = max(array_count_values($pVals));
        $cMax = max(array_count_values($cVals));
        //$pMin = min(array_count_values($pVals));
        //$cMin = min(array_count_values($cVals));
        $pValue = array_keys(array_count_values($pVals), $pMax);
        $cValue = array_keys(array_count_values($cVals), $cMax);
        if ($pHand == "Four of a Kind") {
            if ($pValue > $cValue) {
                $this->playerScore += 1;
                return "Player won!";
            }
            $this->computerScore += 1;
            return "Computer won!";
        }
        if ($pHand == "Three of a Kind") {
            if ($pValue > $cValue) {
                $this->playerScore += 1;
                return "Player won!";
            }
            $this->computerScore += 1;
            return "Computer won!";
        }
        if ($pHand == "Full House") {
            if ($pValue > $cValue) {
                $this->playerScore += 1;
                return "Player won!";
            }
            $this->computerScore += 1;
            return "Computer won!";
        }
        if ($pHand == "Two Pair") {
            //since multiples are max it is an array...
            if ($pValue[0] > $cValue[0]) {
                $this->playerScore += 1;
                return "Player won!";
            }
            if ($pValue[0] == $cValue[0]) {
                foreach ($this->pHand as $pCard) {
                    if ($pCard->value == $pValue[0]) {
                        if ($pCard->suit == "hearts") {
                            $this->playerScore += 1;
                            return "Player won!";
                        }
                    }
                }
            }
            $this->computerScore += 1;
            return "Computer won!";
        }
        if ($pHand == "One Pair") {
            if ($pValue[0] > $cValue[0]) {
                $this->playerScore += 1;
                return "Player won!";
            }
            //return $pValue[0] . $cValue[0];
            if ($pValue[0] == $cValue[0]) {
                foreach ($this->pHand as $pCard) {
                    if ($pCard->value == $pValue[0]) {
                        if ($pCard->suit == "hearts") {
                            $this->playerScore += 1;
                            return "Player won!";
                        }
                    }
                }
            }
            $this->computerScore += 1;
            return "Computer won!";
        }
        //test the suit if flush, royal flush, straight flush
        $suits = ["hearts", "spades", "diamonds", "clubs"];
        $pSuit = array_search($pSuits[0], $suits);
        $cSuit = array_search($cSuits[0], $suits);
        if ($pHand == "Flush") {
            if ($pSuit < $cSuit) {
                $this->playerScore += 1;
                return "Player won!";
            }
            $this->computerScore += 1;
            return "Computer won!";
        }
        if ($pHand == "Royal Flush") {
            if ($pSuit < $cSuit) {
                $this->playerScore += 1;
                return "Player won!";
            }
            $this->computerScore += 1;
            return "Computer won!";
        }
        $pHigh = max($pVals);
        $cHigh = max($cVals);
        if ($pHand == "Straight Flush") {
            if ($pHigh > $cHigh) {
                $this->playerScore += 1;
                return "Player won!";
            }
            if ($pHigh < $cHigh) {
                $this->computerScore += 1;
                return "Computer won!";
            }
            if ($pSuit < $cSuit) {
                $this->playerScore += 1;
                return "Player won!";
            }
            $this->computerScore += 1;
            return "Computer won!";
        }
        //check highest value card and if they same go by suit
        //this is only for straight and high card
        //$pHigh = max($pVals);
        //$cHigh = max($cVals);
        if ($pHigh > $cHigh) {
            $this->playerScore += 1;
            return "Player won!";
        }
        if ($pHigh == $cHigh) {
            //need to cycle through until i get highest val
            //compare their suits
            $compPlay = "";
            $compCompute = "";
            foreach ($this->pHand as $pCard) {
                if ($pCard->value == $pHigh) {
                    $compPlay = $pCard->suit;
                }
            }
            foreach ($this->cHand as $cCard) {
                if ($cCard->value == $cHigh) {
                    $compCompute = $cCard->suit;
                }
            }
            $pCompSuit = array_search($compPlay, $suits);
            $cCompSuit = array_search($compCompute, $suits);
            if ($pCompSuit < $cCompSuit) {
                $this->playerScore += 1;
                return "Player won!";
            }
        }
        $this->computerScore += 1;
        return "Computer won!";
    }
}
