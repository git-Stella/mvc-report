<?php

namespace App\Card;

//use App\Card\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckOfCards.
 */
class DeckOfJokersTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateDeck()
    {
        $deck = new DeckOfJokers();
        $this->assertInstanceOf("\App\Card\DeckOfJokers", $deck);

        //$res = $die->getAsString();
        //$this->assertNotEmpty($res);
    }
    public function testGetNumberCards()
    {
        $deck = new DeckOfJokers();
        $num = $deck->getNumberCards();
        $this->assertEquals($num, 54);
        $deck->remove("joker-joker");
        $num = $deck->getNumberCards();
        $this->assertEquals($num, 53);
    }
}
