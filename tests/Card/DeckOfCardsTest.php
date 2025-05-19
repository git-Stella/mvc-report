<?php

namespace App\Card;

//use App\Card\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckOfCards.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateDeck()
    {
        $deck = new DeckOfCards();
        $this->assertInstanceOf("\App\Card\DeckOfCards", $deck);

        //$res = $die->getAsString();
        //$this->assertNotEmpty($res);
    }
    public function testShuffle()
    {
        $deck = new DeckOfCards();
        $compare = $deck->deck;
        $deck->shuffle();
        $this->assertNotEquals($deck->deck, $compare);
    }
    public function testSort()
    {
        $deck = new DeckOfCards();
        $compare = $deck->deck;
        $deck->shuffle();
        $deck->sort();
        $this->assertEquals($deck->deck, $compare);
    }
    public function testSwapShuffle()
    {
        $deck = new DeckOfCards();
        $compare = $deck->deck;
        $deck->shuffle();
        $deck->swapShuffle($compare);
        $this->assertEquals($deck->deck, $compare);
    }
    public function testGetNumberCards()
    {
        $deck = new DeckOfCards();
        $num = $deck->getNumberCards();
        $this->assertEquals($num, 52);
        $deck->remove("jack-hearts");
        $num = $deck->getNumberCards();
        $this->assertEquals($num, 51);
    }
}
