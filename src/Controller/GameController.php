<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\Card;
//use App\Card\CardGraphic;
use App\Card\DeckOfCards;
use App\Card\DeckOfJokers;
use App\Game\Player;
use App\Game\Bank;
//use App\Dice\DiceHand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

//put version of deck with jokers in json api...
class GameController extends AbstractController
{
    #[Route("/game", name: "game_start")]
    public function home(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfJokers();
        if (null === $session->get("deck")) {
            //$session->get("deck");
            $session->set("deck", count($deck->deck));
        } /*else {
            $session->set("deck", count($deck->deck));
        }*/
        if (null === $session->get("deckArray")) {
            //$session->get("deckArray");
            $session->set("deckArray", $deck->deck);
        } //else {
        /*$cardArray = [];
        foreach ($deck->deck as $card) {
            $suit = $card->getColor();
            $val = $card->getKingdom();
            $cardArray[] = '[' . $suit . $val . ']';
        }
        $session->set("deckArray", $cardArray);*/
        //$session->set("deckArray", $deck->deck);*/
        //}
        return $this->render('game/home.html.twig');
    }
    #[Route("/game/doc", name: "game_doc")]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }
    #[Route("/game/player", name: "game_doc")]
    public function playerTurn(
        SessionInterface $session
    ): Response
    {
        return $this->render('game/doc.html.twig');
    }
    #[Route("/game/test", name: "game_test")]
    public function testinger(): Response
    {
        $bank = new Bank();
        $player = new Player();
        $deck = new DeckOfJokers();
        $deck->shuffle();
        //$i = 0;
        /*$drawn = $deck->draw(3);
        $keepGoing = "keep going";
        foreach ($drawn as $card) {
            if ($keepGoing = "keep going")
            {
                $keepGoing = $bank->draw($card);
            }
            //$bank->draw($card);
        }*/
        $bank->drawCards($deck, 3);
        $player->drawCard($deck);
        $player->drawCard($deck);
        $player->drawCard($deck);
        $data = [
            "hand" => $bank->playHand(),
            "phand" => $player->playHand(),
            "deck" => $deck->returnDeck(),
            "deckNum" => $deck->getNumberCards()
        ];
        return $this->render('game/test.html.twig', $data);
    }
}
