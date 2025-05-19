<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceGraphicTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testRepresentation()
    {
        $die = new DiceGraphic();
        $this->assertInstanceOf("\App\Dice\DiceGraphic", $die);

        $res = $die->getAsString();
        $this->assertNotEmpty($res);
    }
}
