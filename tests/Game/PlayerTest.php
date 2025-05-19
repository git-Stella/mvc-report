<?php

namespace App\Game;

use App\Card\DeckOfCards;
use App\Card\Card;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class PlayerTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreatePlayer()
    {
        $player = new Player();
        $this->assertInstanceOf("\App\Game\Player", $player);

        //$res = $die->getAsString();
        //$this->assertNotEmpty($res);
    }
    public function testDrawCard()
    {
        $player = new Player();
        $deck = new DeckOfCards();
        $card = new Card();

        $res = $player->draw($card);
        $this->assertEquals($res, "drawn");

        $compare = $deck->deck[51];
        $player->drawCard($deck);
        $this->assertEquals($player->hand[1], $compare);
    }
    public function testPlayHand()
    {
        $player = new Player();
        $deck = new DeckOfCards();
        for ($i = 0; $i < 5; $i++) {
            $player->drawCard($deck);
        }
        $shownHand = $player->showHand();
        $this->assertNotEmpty($player->hand);
        $playedHand = $player->playHand();
        $this->assertEmpty($player->hand);
        $this->assertEquals($shownHand, $playedHand);
    }
    public function testCalcPoints()
    {
        $player = new Player();
        $joker = new Card();
        $ace = new Card();
        $joker->setValue(13, "joker");
        $ace->setValue(0, "hearts");
        $player->draw($joker);
        $player->draw($ace);
        $testArray = $player->calcPoints();
        $compareArray = [1, 14, 16];
        $this->assertEquals($testArray, $compareArray);
    }
}
