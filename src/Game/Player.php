<?php

namespace App\Game;

//use App\Card\DeckOfJokers;
use App\Card\Card;

/**
 * Class representing the player in the game 21.
 */
class Player
{
    public $hand;
    /**
     * Constructor for player class.
     */
    public function __construct()
    {
        $this->hand = [];
    }
    /**
     * Method to draw cards to hand.
     */
    public function draw($card): string
    {
        $this->hand[] = $card;
        return "drawn";
    }
    /**
     * Method to play the hand, emptying it.
     */
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
    /**
     * Method for showing the hand, giving a sneak peak.
     */
    public function showHand(): array
    {
        $hand = [];
        foreach ($this->hand as $card) {
            $suit = $card->getColor();
            $val = $card->getKingdom();
            $hand[] = '[' . $suit . $val . ']';
        }
        return $hand;
    }
    /**
     * Method for calculating how many points a hand is worth.
     */
    public function calcPoints(): array
    {
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
            }
            if ($card->getValue() == 13) {
                $val = 0;
            }
            $points2 += $val;
        }
        foreach ($this->hand as $card) {
            $val = $card->getValue() + 1;
            if ($card->getValue() == 13) {
                $val = 15;
            }
            $points3 += $val;
        }
        $pointArray = [$points1, $points2, $points3];
        return $pointArray;
    }
    /**
     * Method to draw cards from a deck.
     */
    public function drawCard($deck): void
    {
        $card = array_pop($deck->deck);
        $this->draw($card);
    }
}
