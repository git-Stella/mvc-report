<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateCard()
    {
        $card = new Card();
        $this->assertInstanceOf("\App\Card\Card", $card);

        $res = $card->getAsString();
        $this->assertNotEmpty($res);
    }
    public function testSetValues()
    {
        $card = new Card();
        $res = $card->setValue(1, "spades");
        $this->assertEquals($res, "1 and spades");
        $this->assertNotEmpty($card->value);
        $this->assertNotEmpty($card->kingdom);
        $this->assertNotEmpty($card->suit);
        $this->assertNotEmpty($card->color);
    }
    public function testValFromKingdom()
    {
        $card = new Card();
        $res = $card->valFromKingdom("jack", "spades");
        $this->assertEquals($res, "10 and spades");
    }
    public function testGetFunctions()
    {
        $card = new Card();
        $res = $card->setValue(0, "spades");
        $this->assertEquals($card->getValue(), 0);
        $this->assertEquals($card->getSuit(), "spades");
        $this->assertEquals($card->getColor(), "♠️");
        $this->assertEquals($card->getKingdom(), "A");
        $this->assertEquals($card->getCard(), "♠️?A");
        //$this->assertEquals($card->getAsString(), "[{0}]");
    }
}
