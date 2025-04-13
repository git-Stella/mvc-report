<?php

namespace App\Card;

class Card
{
    protected $value;

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
            "hearts" => "&#9829;",
            "spades" => "&spadesuit;",
            "clubs" => "&clubs",
            "diamonds" => "&diamondsuit;",
            "joker" => "&#x1F0CF;"
        ];
    }

    public function setValue($val, $suit): string
    {
        $this->value = $val;
        $this->suit = $suit;
        return "$this->value and $this->suit";
    }

    public function valFromKingdom($role, $faction): void
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
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getColor(): string
    {
        return $this->color[$this->suit];
    }

    public function getKingdom(): string
    {
        return $this->kingdom[$this->value];
    }

    public function getCard(): string
    {
        $cardVal = $this->kingdom[$this->value];
        $cardSuit = $this->color[$this->suit];
        return "$cardSuit?$cardVal";
    }

    public function getAsString(): string
    {
        return "[{$this->value}]";
    }
}
