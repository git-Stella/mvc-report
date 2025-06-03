<?php

namespace App\Card;

/**
 * Class representing playing cards
 */
class Card
{
    public $value;
    public $kingdom;
    public $suit;
    public $color;
    /**
     * Constructor method.
     */
    public function __construct()
    {
        $this->value = null;
        $dict = [
            0 => "A",
            1 => "2",
            2 => "3",
            3 => "4",
            4 => "5",
            5 => "6",
            6 => "7",
            7 => "8",
            8 => "9",
            9 => "10",
            10 => "jack",
            11 => "queen",
            12 => "king",
            13 => "joker"
        ];
        $this->kingdom = $dict;
        $this->suit = null;
        $this->color = [
            "hearts" => "♥️",
            "spades" => "♠️",
            "clubs" => "♣️",
            "diamonds" => "♦️",
            "joker" => "🃏︎"
        ];
    }

    /**
     * Method to set value and suit on the card.
     */
    public function setValue($val, $suit): string
    {
        $this->value = $val;
        $this->suit = $suit;
        return "$this->value and $this->suit";
    }

    /**
     * Method to get the value based on what card it is.
     */
    public function valFromKingdom($role, $faction): string
    {
        $dict = [
            "A" => 0,
            "2" => 1,
            "3" => 2,
            "4" => 3,
            "5" => 4,
            "6" => 5,
            "7" => 6,
            "8" => 7,
            "9" => 8,
            "10" => 9,
            "jack" => 10,
            "queen" => 11,
            "king" => 12,
            "joker" => 13
        ];
        $val = $dict[$role];
        $this->setValue($val, $faction);
        return "$this->value and $this->suit";
    }

    /**
     * Method to get value.
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Method to get suit.
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * Method to get color.
     */
    public function getColor(): string
    {
        return $this->color[$this->suit];
    }

    /**
     * Method to get what card it is.
     */
    public function getKingdom(): string
    {
        return $this->kingdom[$this->value];
    }
}
