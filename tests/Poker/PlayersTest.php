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
    public function testScore()
    {
        $players = new Players();
        $rules = new Rules();

        $card1 = new Card();
        $card1->setValue(2, "diamonds");
        $card2 = new Card();
        $card2->setValue(2, "hearts");
        $card3 = new Card();
        $card3->setValue(2, "clubs");
        $card4 = new Card();
        $card4->setValue(11, "clubs");
        $card5 = new Card();
        $card5->setValue(11, "diamonds");
        $hand = [$card1, $card2, $card3, $card4, $card5];
        $players->cHand = $hand;

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
        $players->pHand = $hand0;
        //with this setup player should win

        $card001 = new Card();
        $card001->setValue(0, "spades");
        $card002 = new Card();
        $card002->setValue(9, "spades");
        $card003 = new Card();
        $card003->setValue(10, "spades");
        $card004 = new Card();
        $card004->setValue(11, "spades");
        $card005 = new Card();
        $card005->setValue(12, "spades");
        $hand00 = [$card001, $card002, $card003, $card004, $card005];

        $playerWin = $players->score($rules);
        $this->assertEquals($playerWin, "playerWin");
        $players->pHand = $hand;
        $players->cHand = $hand0;
        $computerWin = $players->score($rules);
        $this->assertEquals($computerWin, "computerWin");
        $players->pHand = $hand00;
        $tie = $players->score($rules);
        $this->assertEquals($tie, "tie");
    }
    /**
     * Checks if the clash method correctly names who won.
     */
    public function testClash()
    {
        $players = new Players();
        //$rules = new Rules();

        $card1 = new Card();
        $card1->setValue(2, "diamonds");
        $card2 = new Card();
        $card2->setValue(2, "hearts");
        $card3 = new Card();
        $card3->setValue(2, "clubs");
        $card4 = new Card();
        $card4->setValue(11, "clubs");
        $card5 = new Card();
        $card5->setValue(11, "diamonds");
        $hand = [$card1, $card2, $card3, $card4, $card5];
        $players->cHand = $hand;

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
        $players->pHand = $hand0;
        //with this setup player should win

        $card001 = new Card();
        $card001->setValue(0, "spades");
        $card002 = new Card();
        $card002->setValue(9, "spades");
        $card003 = new Card();
        $card003->setValue(10, "spades");
        $card004 = new Card();
        $card004->setValue(11, "spades");
        $card005 = new Card();
        $card005->setValue(12, "spades");
        $hand00 = [$card001, $card002, $card003, $card004, $card005];

        $playerWin = $players->clash();
        $this->assertEquals($playerWin, "Player won!");
        $players->pHand = $hand;
        $players->cHand = $hand0;
        $computerWin = $players->clash();
        $this->assertEquals($computerWin, "Computer won!");
        $players->pHand = $hand00;
        $tie = $players->clash();
        $this->assertEquals($tie, "Computer won!");
    }
    /**
     * Checks if the resolveEqual function handles the ties right.
     */
    public function testResolveEqual()
    {
        //so many possible hands... cannot possibly test all of them.
        //Test some of the more difficult ones?
        //Make sure those who work the same as each other one works?
        //test straight, straight flush, royal flush I know works from previous test but test here too for completion
        //also test one pair, two pair, four of a kind
        $players = new Players();
        $deck = new DeckOfCards();
        $cards = $deck->deck;
        //can now pick out each card from here^
        //I know each cards exact location pre shuffling
        //hearts, diamonds, spades, clubs
        //13 cards for each starting with ace and going to 2, then in order.
        $heartRoyal = [
            $cards[0], $cards[9], $cards[10], $cards[11], $cards[12]
        ];
        $diamondRoyal = [
            $cards[13], $cards[22], $cards[23], $cards[24], $cards[25]
        ];
        $spadeRoyal = [
            $cards[26], $cards[35], $cards[36], $cards[37], $cards[38]
        ];
        $players->pHand = $diamondRoyal;
        $players->cHand = $spadeRoyal;
        $res = $players->resolveEqual();
        $this->assertEquals($res, "Computer won!");
        $players->pHand = $heartRoyal;
        $res = $players->resolveEqual();
        $this->assertEquals($res, "Player won!");

        $strFlushHigh = [
            $cards[8], $cards[9], $cards[10], $cards[11], $cards[12]
        ];
        $strFlushLow = [
            $cards[7], $cards[6], $cards[5], $cards[4], $cards[3]
        ];
        $strFlushSpade = [
            $cards[34], $cards[35], $cards[36], $cards[37], $cards[38]
        ];
        $players->pHand = $strFlushLow;
        $players->cHand = $strFlushSpade;
        $res = $players->resolveEqual();
        $this->assertEquals($res, "Computer won!");
        $players->pHand = $strFlushHigh;
        $res = $players->resolveEqual();
        $this->assertEquals($res, "Player won!");

        $straightHighR = [
            $cards[8], $cards[35], $cards[10], $cards[11], $cards[12]
        ];
        $straightHighB = [
            $cards[34], $cards[9], $cards[5], $cards[37], $cards[38]
        ];
        $straightLow = [
            $cards[7], $cards[6], $cards[36], $cards[4], $cards[3]
        ];
        $players->pHand = $straightLow;
        $players->cHand = $straightHighB;
        $res = $players->resolveEqual();
        $this->assertEquals($res, "Computer won!");
        $players->pHand = $straightHighR;
        $res = $players->resolveEqual();
        $this->assertEquals($res, "Player won!");

        $fourAce = [
            $cards[0], $cards[13], $cards[26], $cards[39], $cards[42]
        ];
        $fourTwo = [
            $cards[1], $cards[14], $cards[27], $cards[40], $cards[43]
        ];
        /*$fourThree = [
            $cards[2], $cards[15], $cards[28], $cards[41], $cards[30]
        ];*/
        $players->pHand = $fourAce;
        $players->cHand = $fourTwo;
        $res = $players->resolveEqual();
        $this->assertEquals($res, "Player won!");

        $onePairHigh = [
            $cards[0], $cards[13], $cards[10], $cards[8], $cards[6]
        ];
        $onePairLow = [
            $cards[1], $cards[14], $cards[35], $cards[33], $cards[32]
        ];
        $onePairSame = [
            $cards[26], $cards[39], $cards[20], $cards[7], $cards[5]
        ];
        $players->pHand = $onePairLow;
        $players->cHand = $onePairSame;
        $res = $players->resolveEqual();
        $this->assertEquals($res, "Computer won!");
        $players->pHand = $onePairHigh;
        $res = $players->resolveEqual();
        $this->assertEquals($res, "Player won!");

        $twoPairHigh = [
            $cards[0], $cards[13], $cards[1], $cards[14], $cards[37]
        ];
        $twoPairLow = [
            $cards[2], $cards[15], $cards[10], $cards[8], $cards[23]
        ];
        $twoPairSame = [
            $cards[26], $cards[39], $cards[2], $cards[15], $cards[6]
        ];
        $players->pHand = $twoPairLow;
        $players->cHand = $twoPairSame;
        $res = $players->resolveEqual();
        $this->assertEquals($res, "Computer won!");
        $players->pHand = $twoPairHigh;
        $res = $players->resolveEqual();
        $this->assertEquals($res, "Player won!");
    }
}
