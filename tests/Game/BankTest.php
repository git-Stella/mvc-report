<?php

namespace App\Game;

use App\Card\DeckOfCards;
//use App\Card\DeckOfJokers;
use App\Card\Card;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class BankTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateBank()
    {
        $bank = new Bank();
        $this->assertInstanceOf("\App\Game\Bank", $bank);
    }
    public function testDrawCards()
    {
        $bank = new Bank();
        $deck = new DeckOfCards();
        $card = new Card();
        //$card = new Card();
        //$card->setValue(6, "spades");
        //$deck->remove("A-hearts");
        //$response = $bank->draw($card);
        //$this-assertEquals($response, "keep going");
        $this->assertEmpty($bank->hand);
        $bank->drawCards($deck);
        $this->assertNotEmpty($bank->hand);
        $res = $bank->draw($card);
        $this->assertEquals($res, "stop");
    }
    public function testDrawResponses()
    {
        $bank = new Bank();
        $deck = new DeckOfCards();
        $card = new Card();
        $card->setValue(6, "spades");
        //$deck->remove("A-hearts");
        $response = $bank->draw($card);
        $this->assertEquals($response, "keep going");
        $bank->drawCards($deck);
        $response2 = $bank->draw($card);
        $this->assertEquals($response2, "stop");
    }
    public function testPickPoints()
    {
        $bank = new Bank();
        $joker = new Card();
        $aceOfSpades = new Card();
        $twoOfHearts = new Card();
        $kingOfHearts = new Card();
        $queenOfSpades = new Card();

        $aceOfSpades->setValue(0, "spades");
        $queenOfSpades->setValue(11, "spades");
        $kingOfHearts->setValue(12, "hearts");
        $twoOfHearts->setValue(1, "hearts");
        $joker->setValue(13, "joker");

        $bank->draw($aceOfSpades);
        $bank->draw($twoOfHearts);
        $this->assertEquals($bank->pickPoints(), 16);
        $bank->draw($joker);
        $this->assertEquals($bank->pickPoints(), 18);
        $bank->hand[] = $queenOfSpades;
        $this->assertEquals($bank->pickPoints(), 15);
    }
}
