<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
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

        //$res = $die->getAsString();
        //$this->assertNotEmpty($res);
    }
    public function testSetValues()
    {
        $card = new Card();
        $res = $card->setValue(0, "spades");
        $this->assertEquals($res, "0 and spades");
    }
    public function testValFromKingdom()
    {
        $card = new Card();
        $res = $card->valFromKingdom("jack", "spades");
        $this->assertEquals($res, "10 and spades");
    }
}