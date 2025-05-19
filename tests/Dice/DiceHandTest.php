<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceHandTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateDiceHand()
    {
        $hand = new DiceHand();
        //$die1 = new Dice();
        //$die2 = new Dice();
        $this->assertInstanceOf("\App\Dice\DiceHand", $hand);

        //$res = $die->getAsString();
        //$this->assertNotEmpty($res);
    }
    public function testAddDice()
    {
        $hand = new DiceHand();
        $die1 = new Dice();
        $die2 = new Dice();
        $hand->add($die1);
        $hand->add($die2);

        $val1 = $die1->getValue();
        $val2 = $die2->getValue();
        $str1 = $die1->getAsString();
        $str2 = $die2->getAsString();

        $this->assertEquals($hand->getNumberDices(), 2);
        $this->assertEquals($hand->getValues(), [$val1, $val2]);
        $this->assertEquals($hand->getString(), [$str1, $str2]);
    }
}
