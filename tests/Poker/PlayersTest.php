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
    /**
     * Tests so players is constructed right.
     */
    public function testCreatePlayers()
    {
        $players = new Players();
        $rules = new Rules();
        $this->assertInstanceOf("\App\Poker\Players", $players);
        $this->assertEquals($players->rules, $rules);
        /*$testArray = ["a", "a", "b", "c"];
        $testMax = max($testArray);
        echo($testMax);
        $testKey = array_search($testMax, $testArray);
        echo($testKey);*/
    }
    /**
     * Tests suit logic function.
     */
    public function testSuitLogic()
    {
        $players = new Players();
        $deck = new DeckOfCards();
        $deckLen = count($deck->deck);
        $card1 = new Card();
        $card1->setValue(0, "hearts");
        $card2 = new Card();
        $card2->setValue(9, "hearts");
        $card3 = new Card();
        $card3->setValue(10, "hearts");
        $card4 = new Card();
        $card4->setValue(11, "hearts");
        $card5 = new Card();
        $card5->setValue(12, "hearts");
        $hand = [$card1, $card2, $card3, $card4, $card5];
        $players->cHand = $hand;
        $this->assertEquals($players->cHand, $hand);
        $deck->deck = $players->suitLogic($hand, "hearts", $deck);
        $this->assertEquals($players->cHand, $hand);
        $this->assertEquals(count($deck->deck), $deckLen);

        $cardF = new Card();
        $cardF->setValue(0, "spades");
        $cardF2 = new Card();
        $cardF2->setValue(3, "clubs");
        $handF = [$cardF, $cardF2, $card3, $card4, $card5];
        $players->cHand = $handF;
        $this->assertEquals($players->cHand, $handF);
        $deck->deck = $players->suitLogic($handF, "hearts", $deck);
        $this->assertNotEquals($players->cHand, $handF);
        $this->assertNotEquals(count($deck->deck), $deckLen);
    }
    /**
     * Tests value logic function.
     */
    public function testValLogic()
    {
        $players = new Players();
        $deck = new DeckOfCards();
        $rules = new Rules();
        $deckLen = count($deck->deck);
        $card1 = new Card();
        $card1->setValue(0, "hearts");
        $card2 = new Card();
        $card2->setValue(0, "spades");
        $card3 = new Card();
        $card3->setValue(0, "clubs");
        $card4 = new Card();
        $card4->setValue(11, "hearts");
        $card5 = new Card();
        $card5->setValue(11, "spades");
        $hand = [$card1, $card2, $card3, $card4, $card5];
        $players->cHand = $hand;

        $this->assertEquals($players->cHand, $hand);
        $deck->deck = $players->valLogic($hand, 0, $deck);
        $this->assertEquals($players->cHand, $hand);
        $this->assertEquals(count($deck->deck), $deckLen);
        $res = $rules->handPoints($players->cHand);

        $cardF = new Card();
        $cardF->setValue(1, "spades");
        $cardF2 = new Card();
        $cardF2->setValue(3, "clubs");
        $handF = [$cardF, $card2, $card3, $card4, $card5];
        $players->cHand = $handF;
        //rules check
        $this->assertEquals($players->cHand, $handF);
        $deck->deck = $players->valLogic($handF, 0, $deck);
        $this->assertNotEquals($players->cHand, $handF);
        $this->assertNotEquals(count($deck->deck), $deckLen);
        $res2 = $rules->handPoints($players->cHand);

        $handF2 = [$cardF, $cardF2, $card3, $card4, $card5];
        $players->cHand = $handF2;
        $this->assertEquals($players->cHand, $handF2);
        $deck->deck = $players->valLogic($handF2, 0, $deck);
        $this->assertNotEquals($players->cHand, $handF2);
        $this->assertNotEquals(count($deck->deck), $deckLen);
        $res3 = $rules->handPoints($players->cHand);

        $this->assertEquals($res, "Full House");
        $this->assertEquals($res2, "Two Pair");
        $this->assertEquals($res3, "Three of a Kind");
    }
    /**
     * Tests hand logic function to see if it correctly picks val or suit logic.
     */
    public function testHandLogic()
    {
        //need to check if it correctly goes for val or suit logic
        $players = new Players();
        $deck = new DeckOfCards();
        $rules = new Rules();
        $deckLen = count($deck->deck);
        $card1 = new Card();
        $card1->setValue(0, "hearts");
        $card2 = new Card();
        $card2->setValue(0, "spades");
        $card3 = new Card();
        $card3->setValue(0, "clubs");
        $card4 = new Card();
        $card4->setValue(11, "hearts");
        $card5 = new Card();
        $card5->setValue(11, "spades");
        $hand = [$card1, $card2, $card3, $card4, $card5];
        $players->cHand = $hand;

        $this->assertEquals($players->cHand, $hand);
        $deck->deck = $players->handLogic($deck);
        $this->assertEquals($players->cHand, $hand);
        $this->assertEquals(count($deck->deck), $deckLen);
        $res = $rules->handPoints($players->cHand);
        $this->assertEquals($res, "Full House");
        //it goes for vallogic^

        $card01 = new Card();
        $card01->setValue(0, "hearts");
        $card02 = new Card();
        $card02->setValue(9, "hearts");
        $card03 = new Card();
        $card03->setValue(10, "hearts");
        $card04 = new Card();
        $card04->setValue(11, "hearts");
        $card05 = new Card();
        $card05->setValue(12, "hearts");
        $hand0 = [$card01, $card02, $card03, $card04, $card05];
        $players->cHand = $hand0;

        $this->assertEquals($players->cHand, $hand0);
        $deck->deck = $players->handLogic($deck);
        $this->assertEquals($players->cHand, $hand0);
        $this->assertEquals(count($deck->deck), $deckLen);
        $res0 = $rules->handPoints($players->cHand);
        $this->assertEquals($res0, "Royal Flush");
        //it goes for suitlogic^
    }
    /**
     * Checks if the bet method works as intended.
     */
    public function testBet()
    {
        $players = new Players();
        $raise = $players->bet(30);
        //check how big bet is between each
        $call = $players->bet(400);
        $fold = $players->bet(950);
        $players->pBet = 6000;
        $allIn1 = $players->bet(5500);
        $allIn2 = $players->bet(6000);
        $this->assertEquals($raise, "raise");
        $this->assertEquals($call, "call");
        $this->assertEquals($fold, "fold");
        $this->assertEquals($allIn1, "all in");
        $this->assertEquals($allIn2, "all in");
    }
    /**
     * Checks if the score method used within clash works.
     */
}
