<?php

namespace App\Poker;

//use App\Poker\Rules;
use App\Card\Card;
use App\Card\DeckOfCards;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class RulesTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateRules()
    {
        $rules = new Rules();
        $this->assertInstanceOf("\App\Poker\Rules", $rules);
    }
    /**Tests if royal flush works correctly */
    public function testRoyalFlush()
    {
        /*for ($i = 0; $i < 5; $i++) {

        }*/
        $rules = new Rules();
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

        $cardF = new Card();
        $cardF->setValue(0, "spades");
        $handF = [$cardF, $card2, $card3, $card4, $card5];

        $res = $rules->rulesWheel(0, $hand);
        $res2 = $rules->rulesWheel(0, $handF);
        $this->assertTrue($res);
        $this->assertNotTrue($res2);
        $this->assertTrue($rules->royalFlush($hand));
        $this->assertNotTrue($rules->royalFlush($handF));
    }
    public function testStraightFlush()
    {
        $rules = new Rules();
        $card1 = new Card();
        $card1->setValue(8, "hearts");
        $card2 = new Card();
        $card2->setValue(7, "hearts");
        $card3 = new Card();
        $card3->setValue(6, "hearts");
        $card4 = new Card();
        $card4->setValue(5, "hearts");
        $card5 = new Card();
        $card5->setValue(4, "hearts");
        $hand = [$card1, $card2, $card3, $card4, $card5];

        $cardF = new Card();
        $cardF->setValue(8, "spades");
        $handF = [$cardF, $card2, $card3, $card4, $card5];

        $res = $rules->rulesWheel(1, $hand);
        $res2 = $rules->rulesWheel(1, $handF);
        $this->assertTrue($res);
        $this->assertNotTrue($res2);
        $this->assertTrue($rules->straightFlush($hand));
        $this->assertNotTrue($rules->straightFlush($handF));
    }
    public function testFourOfAKind()
    {
        $rules = new Rules();
        $card1 = new Card();
        $card1->setValue(0, "hearts");
        $card2 = new Card();
        $card2->setValue(0, "spades");
        $card3 = new Card();
        $card3->setValue(0, "clubs");
        $card4 = new Card();
        $card4->setValue(0, "diamonds");
        $card5 = new Card();
        $card5->setValue(12, "hearts");
        $hand = [$card1, $card2, $card3, $card4, $card5];

        $cardF = new Card();
        $cardF->setValue(1, "spades");
        $handF = [$cardF, $card2, $card3, $card4, $card5];

        $res = $rules->rulesWheel(2, $hand);
        $res2 = $rules->rulesWheel(2, $handF);
        $this->assertTrue($res);
        $this->assertNotTrue($res2);
        $this->assertTrue($rules->fourOfAKind($hand));
        $this->assertNotTrue($rules->fourOfAKind($handF));
    }
    public function testFullHouse()
    {
        $rules = new Rules();
        $card1 = new Card();
        $card1->setValue(0, "hearts");
        $card2 = new Card();
        $card2->setValue(0, "spades");
        $card3 = new Card();
        $card3->setValue(0, "clubs");
        $card4 = new Card();
        $card4->setValue(11, "hearts");
        $card5 = new Card();
        $card5->setValue(11, "diamonds");
        $hand = [$card1, $card2, $card3, $card4, $card5];

        $cardF = new Card();
        $cardF->setValue(3, "spades");
        $handF = [$cardF, $card2, $card3, $card4, $card5];

        $res = $rules->rulesWheel(3, $hand);
        $res2 = $rules->rulesWheel(3, $handF);
        $this->assertTrue($res);
        $this->assertNotTrue($res2);
        $this->assertTrue($rules->fullHouse($hand));
        $this->assertNotTrue($rules->fullHouse($handF));
    }
    public function testFlush()
    {
        $rules = new Rules();
        $card1 = new Card();
        $card1->setValue(0, "hearts");
        $card2 = new Card();
        $card2->setValue(9, "hearts");
        $card3 = new Card();
        $card3->setValue(7, "hearts");
        $card4 = new Card();
        $card4->setValue(3, "hearts");
        $card5 = new Card();
        $card5->setValue(12, "hearts");
        $hand = [$card1, $card2, $card3, $card4, $card5];

        $cardF = new Card();
        $cardF->setValue(0, "spades");
        $handF = [$cardF, $card2, $card3, $card4, $card5];

        $res = $rules->rulesWheel(4, $hand);
        $res2 = $rules->rulesWheel(4, $handF);
        $this->assertTrue($res);
        $this->assertNotTrue($res2);
        $this->assertTrue($rules->flush($hand));
        $this->assertNotTrue($rules->flush($handF));
    }
    public function testStraight()
    {
        $rules = new Rules();
        $card1 = new Card();
        $card1->setValue(0, "spades");
        $card2 = new Card();
        $card2->setValue(9, "diamonds");
        $card3 = new Card();
        $card3->setValue(10, "hearts");
        $card4 = new Card();
        $card4->setValue(11, "clubs");
        $card5 = new Card();
        $card5->setValue(12, "hearts");
        $hand = [$card1, $card2, $card3, $card4, $card5];

        $cardF = new Card();
        $cardF->setValue(1, "spades");
        $handF = [$cardF, $card2, $card3, $card4, $card5];

        $cardA = new Card();
        $cardA->setValue(8, "spades");
        $handA = [$cardA, $card2, $card3, $card4, $card5];

        $res = $rules->rulesWheel(5, $hand);
        $res2 = $rules->rulesWheel(5, $handF);
        $res3 = $rules->rulesWheel(5, $handA);
        $this->assertTrue($res);
        $this->assertNotTrue($res2);
        $this->assertTrue($rules->straight($hand));
        $this->assertNotTrue($rules->straight($handF));
        $this->assertTrue($rules->straight($handA));
    }
    public function testThreeOfAKind()
    {
        $rules = new Rules();
        $card1 = new Card();
        $card1->setValue(0, "hearts");
        $card2 = new Card();
        $card2->setValue(0, "clubs");
        $card3 = new Card();
        $card3->setValue(0, "spades");
        $card4 = new Card();
        $card4->setValue(3, "hearts");
        $card5 = new Card();
        $card5->setValue(12, "hearts");
        $hand = [$card1, $card2, $card3, $card4, $card5];

        $cardF = new Card();
        $cardF->setValue(1, "spades");
        $handF = [$cardF, $card2, $card3, $card4, $card5];

        $res = $rules->rulesWheel(6, $hand);
        $res2 = $rules->rulesWheel(6, $handF);
        $this->assertTrue($res);
        $this->assertNotTrue($res2);
        $this->assertTrue($rules->threeOfAKind($hand));
        $this->assertNotTrue($rules->threeOfAKind($handF));
    }
    public function testTwoPair()
    {
        $rules = new Rules();
        $card1 = new Card();
        $card1->setValue(0, "hearts");
        $card2 = new Card();
        $card2->setValue(0, "spades");
        $card3 = new Card();
        $card3->setValue(7, "hearts");
        $card4 = new Card();
        $card4->setValue(7, "clubs");
        $card5 = new Card();
        $card5->setValue(12, "hearts");
        $hand = [$card1, $card2, $card3, $card4, $card5];

        $cardF = new Card();
        $cardF->setValue(3, "spades");
        $handF = [$cardF, $card2, $card3, $card4, $card5];

        $res = $rules->rulesWheel(7, $hand);
        $res2 = $rules->rulesWheel(7, $handF);
        $this->assertTrue($res);
        $this->assertNotTrue($res2);
        $this->assertTrue($rules->twoPair($hand));
        $this->assertNotTrue($rules->twoPair($handF));
    }
    public function testOnePair()
    {
        $rules = new Rules();
        $card1 = new Card();
        $card1->setValue(0, "hearts");
        $card2 = new Card();
        $card2->setValue(0, "spades");
        $card3 = new Card();
        $card3->setValue(7, "hearts");
        $card4 = new Card();
        $card4->setValue(3, "hearts");
        $card5 = new Card();
        $card5->setValue(12, "hearts");
        $hand = [$card1, $card2, $card3, $card4, $card5];

        $cardF = new Card();
        $cardF->setValue(1, "spades");
        $handF = [$cardF, $card2, $card3, $card4, $card5];

        $res = $rules->rulesWheel(8, $hand);
        $res2 = $rules->rulesWheel(8, $handF);
        $this->assertTrue($res);
        $this->assertNotTrue($res2);
        $this->assertTrue($rules->onePair($hand));
        $this->assertNotTrue($rules->onePair($handF));
    }
    public function testHighCard()
    {
        $rules = new Rules();
        $card1 = new Card();
        $card1->setValue(0, "hearts");
        $card2 = new Card();
        $card2->setValue(9, "spades");
        $card3 = new Card();
        $card3->setValue(7, "clubs");
        $card4 = new Card();
        $card4->setValue(3, "hearts");
        $card5 = new Card();
        $card5->setValue(12, "hearts");
        $hand = [$card1, $card2, $card3, $card4, $card5];

        $res = $rules->rulesWheel(9, $hand);
        $this->assertTrue($res);
        $this->assertTrue($rules->highCard($hand));
    }
    /*public function testPlacehold()
    {
        $rules = new Rules();
        $card1 = new Card();
        $card1->setValue(0, "diamonds");
        $card2 = new Card();
        $card2->setValue(0, "clubs");
        $card3 = new Card();
        $card3->setValue(0, "hearts");
        $card4 = new Card();
        $card4->setValue(0, "spades");
        $card5 = new Card();
        $card5->setValue(12, "spades");
        $hand = [$card1, $card2, $card3, $card4, $card5];
        $res = $rules->rulesWheel(2, $hand);
        $this->assertTrue($res);
        /*$res2 = $rules->rulesWheel(9, $hand);
        $res3 = $rules->rulesWheel(1, $hand);
        $this->assertNotTrue($res3);
        $this->assertTrue($res2);
        $this->assertTrue($res);
    }*/
    /*public function testSetValues()
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
        $card->setValue(0, "spades");
        $this->assertEquals($card->getValue(), 0);
        $this->assertEquals($card->getSuit(), "spades");
        $this->assertEquals($card->getColor(), "♠️");
        $this->assertEquals($card->getKingdom(), "A");
    }*/
}
