<?php

namespace App\Card;

class CardGraphic extends Card
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCard(): string
    {
        return "placehold";
    }
}
