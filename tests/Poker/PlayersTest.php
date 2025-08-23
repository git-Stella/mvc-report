<?php

namespace App\Poker;

//use App\Poker\Rules;
use App\Card\Card;
use App\Card\DeckOfCards;
use App\Poker\Rules;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class players representing poker players.
 */
class PlayersTest extends TestCase
{
    public function testCreatePlayers()
    {
        $players = new Players();
        $this->assertInstanceOf("\App\Poker\Players", $players);
        /*$testArray = ["a", "a", "b", "c"];
        $testMax = max($testArray);
        echo($testMax);
        $testKey = array_search($testMax, $testArray);
        echo($testKey);*/
    }
}
